<?php
/**
 *Created by PhpStorm
 *Created at ১৩/৯/২১ ৫:০৮ PM
 */

namespace App\Http\Controllers\Ap;


use App\Contracts\Ap\ApContract;
use App\Contracts\Ap\ApLookupContract;
use App\Contracts\Gl\GlContract;
use App\Contracts\LookupContract;
use App\Entities\Ap\FasApVendors;
use App\Entities\BudgetManagement\FasBudgetHead;
use App\Entities\Common\LApInvoiceType;
use App\Entities\Common\LBillRegister;
use App\Entities\Common\LBillSection;
use App\Entities\Common\LCostCenterDept;
use App\Entities\Common\LCurrency;
use App\Entities\Gl\GlCoa;
use App\Entities\Gl\GlCoaParams;
use App\Enums\Ap\ApFunType;
use App\Enums\BudgetMonitoring\BmnFunctionType;
use App\Enums\Common\LGlInteFun;
use App\Enums\Common\LGlInteModules;
use App\Enums\Common\LTransAmtType;
use App\Enums\Gl\FunctionTypes;
use App\Enums\ModuleInfo;
use App\Enums\ProActionType;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Enums\WorkFlowRoleKey;
use App\Enums\YesNoFlag;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Ap\ApLookupManager;
use App\Managers\Ap\ApManager;
use App\Managers\Ar\ArLookupManager;
use App\Managers\BudgetMonitoring\BudgetMonitoringLookupManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceBillEntryController extends Controller
{
    private $lookupManager;
    private $glManager;
    private $glCoa;
    private $glCoaParam;
    private $currency;
    private $budgetMonitoringLookupManager;
    private $arLookupManager;

    /** @var ApLookupManager */
    private $apLookupManager;

    /** @var ApManager */
    private $apManager;

    public function __construct(LookupContract $lookupManager, ArLookupManager $arLookupManager, GlContract $glManager, ApLookupContract $apLookupManager, ApContract $apManager)
    {
        $this->lookupManager = $lookupManager;
        $this->glManager = $glManager;
        $this->glCoa = new GlCoa();
        $this->glCoaParam = new GlCoaParams();
        $this->currency = new LCurrency();
        $this->apLookupManager = $apLookupManager;
        $this->apManager = $apManager;
        $this->arLookupManager = $arLookupManager;
        $this->budgetMonitoringLookupManager = new BudgetMonitoringLookupManager();
    }

    public function index()
    {
        $user_id = auth()->id();
        //dd($user_id);

        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        //$postingDate = $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id);
        $department = $this->lookupManager->getDeptCostCenter();
        $billSecs = $this->lookupManager->getBillSections(ApFunType::AP_INVOICE_BILL_ENTRY);
        //$billRegs = $this->lookupManager->getBillRegisterOnFunction(ApFunType::AP_INVOICE_BILL_ENTRY);
        $vendors = $this->apLookupManager->getVendors();
        $vendorType = $this->apLookupManager->getVendorTypes();
        $vendorCategory = $this->apLookupManager->getVendorCategory();
        $customerCategory = $this->arLookupManager->getCustomerCategory();

        //$data['subsidiary_type'] = $this->lookupManager->findPartySubLedger(LGlInteModules::ACC_PAY_VENDOR);
        //$data['subsidiary_type'] = $this->lookupManager->findExcVatTaxPartySubLedger(LGlInteModules::ACC_PAY_VENDOR);
        $data['subsidiary_type'] = $this->apLookupManager->getPartySubLedger(ApFunType::AP_INVOICE_BILL_ENTRY); //Add Party Sub-Ledger function wise :pavel-06-04-2022
        $data['invoice_type'] = $this->apLookupManager->findInvoiceType();
        $data['currency'] = $this->currency->get();
        $coaParams = $this->lookupManager->getSpecifiedGlCoaParams([\App\Enums\Common\GlCoaParams::ASSET, \App\Enums\Common\GlCoaParams::EXPENSE, \App\Enums\Common\GlCoaParams::LIABILITY, \App\Enums\Common\GlCoaParams::INCOME]); //Add LIABILITY Part :pavel-31-01-22
        $paymentTerms = $this->lookupManager->getPaymentTerms();
        $paymentMethod = $this->lookupManager->getPaymentMethods();
        $lastPostingBatch = $this->lookupManager->findLastPostingBatchId(LGlInteModules::ACC_PAY_VENDOR, ApFunType::AP_INVOICE_BILL_ENTRY, $user_id);
        //$roleWiseUser = $this->apManager->findRoleWiseUser(WorkFlowMaster::AP_INVOICE_BILL_ENTRY_APPROVAL, WorkFlowRoleKey::AP_INVOICE_BILL_ENTRY_MAKE, $user_id);  //Add & call this function  :pavel-07-04-2022
        $isRequired = [
            'document_required' => (DB::selectOne("select fas_policy.get_live_deploy_policy_flag from dual")->get_live_deploy_policy_flag == 1 ? 'readonly' : 'required' )
        ];
        return view('ap.invoice-bill-entry.index', compact('isRequired','customerCategory', 'fiscalYear', 'vendors', 'paymentMethod', 'paymentTerms', 'department', 'billSecs', 'data', 'coaParams', 'vendorType', 'vendorCategory', 'lastPostingBatch'));
    }

    public function insert(Request $request, $id = null)
    {
        DB::beginTransaction();
        try {
            $invoice_id = isset($id) ? $id : '';
            $actionType = isset($id) ? ProActionType::UPDATE : ProActionType::INSERT;
            $status_code = sprintf("%4000d", "");
            $status_message = sprintf("%4000s", "");
            $trans_batch_id = sprintf("%4000s", "");
            $o_document_no = sprintf("%4000s", "");

            $params = [
                'p_module_id' => LGlInteModules::ACC_PAY_VENDOR,
                'p_function_id' => ApFunType::AP_INVOICE_BILL_ENTRY,
                'p_gl_subsidiary_id' => $request->post('ap_party_sub_ledger'),
                'p_invoice_type_id' => $request->post('ap_invoice_type'),
                'p_vendor_id' => $request->post('ap_vendor_id'),
                'p_emp_id' => '',
                'p_purchase_rcv_mst_id' => ($request->post('po_master_id') != null) ? $request->post('po_master_id') : '',
                'p_po_date' => ($request->post('ap_purchase_order_date') != null) ? HelperClass::dateFormatForDB($request->post('ap_purchase_order_date')) : '',
                'p_po_number' => ($request->post('ap_purchase_order_no') != null) ? $request->post('ap_purchase_order_no') : '',
                //'p_budget_booking_id'=> ($request->post('b_booking_id') != null) ? $request->post('b_booking_id') : '',
                'p_budget_head_id' => ($request->post('b_head_id') != null) ? $request->post('b_head_id') : '',
                'p_trans_period_id' => $request->post('period'),
                'p_trans_date' => HelperClass::dateFormatForDB($request->post('posting_date')),
                'p_document_date' => HelperClass::dateFormatForDB($request->post('document_date')),
                'p_document_no' => $request->post('document_number'),
                'p_document_ref' => $request->post('document_reference'),
                'p_narration' => $request->post('narration'),
                'p_department_id' => $request->post('department'),
                'P_budget_dept_id' => $request->post('budget_department'), /********Added on: 06/06/2022, sujon**********/
                'p_bill_sec_id' => $request->post('bill_section'),
                'p_bill_reg_id' => $request->post('bill_register'),
                'p_currency_code' => $request->post('ap_payment_currency'),
                'p_exchange_rate' => $request->post('ap_exchange_rate'),
                'p_invoice_amount_ccy' => ($request->post('ap_invoice_amount_ccy') != "") ? $request->post('ap_invoice_amount_ccy') : 0,
                'p_invoice_amount_lcy' => ($request->post('ap_invoice_amount_lcy') != "") ? $request->post('ap_invoice_amount_lcy') : 0,
                'p_inclusive_tax_vat_yn' => ($request->post('ap_inclusive_tax_vat') != "") ? $request->post('ap_inclusive_tax_vat') : 'N',
                'p_tax_amount_pct' => ($request->post('ap_tax_amount_ccy_percentage') != "") ? $request->post('ap_tax_amount_ccy_percentage') : 0,
                'p_tax_amount_ccy' => ($request->post('ap_tax_amount_ccy') != "") ? $request->post('ap_tax_amount_ccy') : 0,
                'p_tax_amount_lcy' => ($request->post('ap_tax_amount_lcy') != "") ? $request->post('ap_tax_amount_lcy') : 0,
                'p_tax_party_id' => ($request->post('party_name_for_tax') != "") ? $request->post('party_name_for_tax') : '',

                'p_vat_amount_pct' => ($request->post('ap_vat_amount_ccy_percentage') != "") ? $request->post('ap_vat_amount_ccy_percentage') : 0,
                'p_vat_amount_ccy' => ($request->post('ap_vat_amount_ccy') != "") ? $request->post('ap_vat_amount_ccy') : 0,
                'p_vat_amount_lcy' => ($request->post('ap_vat_amount_lcy') != "") ? $request->post('ap_vat_amount_lcy') : 0,
                'p_vat_party_id' => ($request->post('party_name_for_vat') != "") ? $request->post('party_name_for_vat') : '',

                'p_security_deposit_pct' => ($request->post('ap_security_deposit_amount_ccy_percentage') != "") ? $request->post('ap_security_deposit_amount_ccy_percentage') : 0,
                'p_security_deposit_ccy' => ($request->post('ap_security_deposit_amount_ccy') != "") ? $request->post('ap_security_deposit_amount_ccy') : 0,
                'p_security_deposit_lcy' => ($request->post('ap_security_deposit_amount_lcy') != "") ? $request->post('ap_security_deposit_amount_lcy') : 0,

                'p_extra_security_deposit_pct' => ($request->post('ap_extra_security_deposit_amount_ccy_percentage') != "") ? $request->post('ap_extra_security_deposit_amount_ccy_percentage') : 0,
                'p_extra_security_deposit_ccy' => ($request->post('ap_extra_security_deposit_amount_ccy') != "") ? $request->post('ap_extra_security_deposit_amount_ccy') : 0,
                'p_extra_security_deposit_lcy' => ($request->post('ap_extra_security_deposit_amount_lcy') != "") ? $request->post('ap_extra_security_deposit_amount_lcy') : 0,

                'p_other_amount_ccy' => ($request->post('ap_total_add_amount_ccy') != "") ? $request->post('ap_total_add_amount_ccy') : 0,
                'p_other_amount_lcy' => ($request->post('ap_total_add_amount_lcy') != "") ? $request->post('ap_total_add_amount_lcy') : 0,
                'p_payable_amount_ccy' => ($request->post('ap_payable_amount_ccy') != "") ? $request->post('ap_payable_amount_ccy') : 0,
                'p_payable_amount_lcy' => ($request->post('ap_payable_amount_lcy') != "") ? $request->post('ap_payable_amount_lcy') : 0,
                'p_payment_terms_id' => $request->post('ap_payment_terms'),
                'p_payment_due_date' => HelperClass::dateFormatForDB($request->post('ap_payment_due_date')),
                'p_payment_methods_id' => $request->post('ap_payment_method'),
                'p_payment_hold_flag' => ($request->post('ap_hold_all_payment') == '1') ? '1' : 0,
                'p_payment_hold_reason' => ($request->post('ap_hold_all_payment') == '1') ? $request->post('ap_hold_all_payment_reason') : '',
                //'p_switch_payment_vendor_id' => ($request->post('ap_switch_pay_vendor_id') != "") ? $request->post('ap_switch_pay_vendor_id') : NULL, //Block this Pavel-28-08-22
                //'p_switch_payment_vendor_id' => NULL,
                'p_user_id' => auth()->id(),
                'p_system_generated_yn' => YesNoFlag::NO,
                'o_invoice_id' => [
                    'value' => &$invoice_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'o_trans_batch_id' => &$trans_batch_id,
                'o_document_no' => &$o_document_no,
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message
            ];

            DB::executeProcedure('CPAACC.fas_ap_trans.trans_ap_invoice_entry', $params);

            if ($params['o_status_code'] != "1") {
                DB::rollBack();
                return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
            } else {
                $addLine = $request->post('addLine');
                //dd($addLine);
                if ($request->post('ap_total_add_amount_ccy') != '') {
                    foreach ($addLine as $key => $line) {
                        ${'status_code' . $key} = sprintf("%4000s", "");
                        ${'status_message' . $key} = sprintf("%4000s", "");
                        $invoice_line_id = '';

                        ${'params' . $key} = [
                            /*'p_action_type' => $actionType,*/
                            'p_invoice_id' => $invoice_id,
                            /*'p_invoice_line_id' => [
                                'value' => &$invoice_line_id,
                                'type' => \PDO::PARAM_INPUT_OUTPUT,
                                'length' => 255
                            ],*/
                            /*'p_module_id' => LGlInteModules::ACC_PAY_VENDOR,*/
                            'p_module_id' => is_null($line['ap_add_module_id']) ? LGlInteModules::ACC_PAY_VENDOR : $line['ap_add_module_id'],
                            'p_function_id' => ApFunType::AP_INVOICE_BILL_ENTRY,
                            'p_trans_period_id' => (integer)$request->post('period'),
                            /*                            'p_trans_period_name' => $request->post('posting_name'),*/
                            'p_trans_date' => HelperClass::dateFormatForDB($request->post('posting_date')),
                            'p_document_date' => HelperClass::dateFormatForDB($request->post('document_date')),
                            'p_document_no' => $request->post('document_number'),
                            'p_document_ref' => $request->post('document_reference'),
                            'p_narration' => $request->post('narration'),
                            'p_department_id' => (integer)$request->post('department'),
                            //'P_budget_dept_id' => (integer)$request->post('budget_department'),            /********Added on: 06/06/2022, sujon**********/
                            'p_bill_sec_id' => (integer)$request->post('bill_section'),
                            'p_bill_reg_id' => (integer)$request->post('bill_register'),
                            'p_gl_acc_id' => (integer)$line['ap_add_account_code'],
                            'p_gl_subsidiary_id' => ($line['ap_add_party_sub_ledger'] != null) ? $line['ap_add_party_sub_ledger'] : null,  //Add Pavel-07-07-22
                            'p_vendor_id' => ($line['ap_add_vendor_id'] != null) ? $line['ap_add_vendor_id'] : null,   //Add Pavel-07-07-22
                            'p_currency_code' => $request->post('ap_payment_currency'),
                            'p_exchange_rate' => $request->post('ap_exchange_rate'),
                            /*'p_amount_type_id' => LTransAmtType::GENERAL_OTHERS,*/
                            'p_amount_ccy' => $line['ap_add_amount_ccy'],
                            'p_amount_lcy' => $line['ap_add_amount_lcy'],
                            'p_additional_acc_yn' => 'y',
                            /*'p_dr_cr' => '',*/
                            /*'p_reversal_trans_flag' => '',
                            'p_reversal_ref_trans_date' => '',
                            'p_reversal_ref_invoice_id' => '',
                            'p_reversal_ref_invoice_line_id' => '',
                            'p_authorize_status_id' => '',
                            'p_authorize_by' => '',
                            'p_authorize_date' => '',*/
                            'p_user_id' => auth()->id(),
                            'o_status_code' => &${'status_code' . $key},
                            'o_status_message' => &${'status_message' . $key}
                        ];
                        try {
                            DB::executeProcedure('CPAACC.fas_ap_trans.trans_ap_invoice_lines', ${'params' . $key});
                        } catch (\Exception $e) {
                            DB::rollBack();
                            return response()->json(["response_code" => ${"status_code" . $key}, "response_msg" => ${"status_message" . $key} . 'Add Acc']);
                            //Log::info("Internal Error occurred during AP line entry.");
                        }


                        if (${'status_code' . $key} != "1") {
                            DB::rollBack();

                            //throw new \Exception(${"status_message" . $key}, ${'params' . $key});
                            return response()->json(["response_code" => ${"status_code" . $key}, "response_msg" => ${"status_message" . $key} . 'Add Acc']);
                        }
                    }

                }

                $lines = $request->post('line');
                if ($request->post('ap_distribution_flag') == '0') {
                    foreach ($lines as $key => $line) {
                        ${'status_code' . $key} = sprintf("%4000s", "");
                        ${'status_message' . $key} = sprintf("%4000s", "");
                        $invoice_line_id = '';

                        ${'params' . $key} = [
                            /*'p_action_type' => $actionType,*/
                            'p_invoice_id' => $invoice_id,
                            /*'p_invoice_line_id' => [
                                'value' => &$invoice_line_id,
                                'type' => \PDO::PARAM_INPUT_OUTPUT,
                                'length' => 255
                            ],*/
                            'p_module_id' => is_null($line['ap_dist_module_id']) ? LGlInteModules::ACC_PAY_VENDOR : $line['ap_dist_module_id'],
                            'p_function_id' => ApFunType::AP_INVOICE_BILL_ENTRY,
                            'p_trans_period_id' => (integer)$request->post('period'),
                            /*'p_trans_period_name' => $request->post('posting_name'),*/
                            'p_trans_date' => HelperClass::dateFormatForDB($request->post('posting_date')),
                            'p_document_date' => HelperClass::dateFormatForDB($request->post('document_date')),
                            'p_document_no' => $request->post('document_number'),
                            'p_document_ref' => $request->post('document_reference'),
                            'p_narration' => $request->post('narration'),
                            'p_department_id' => (integer)$request->post('department'),
                            //'P_budget_dept_id' => (integer)$request->post('budget_department'),            /********Added on: 06/06/2022, sujon -remove-pavel-07-06-22**********/
                            'p_bill_sec_id' => (integer)$request->post('bill_section'),
                            'p_bill_reg_id' => (integer)$request->post('bill_register'),
                            'p_gl_acc_id' => (integer)$line['ap_account_code'],
                            'p_gl_subsidiary_id' => $line['ap_dist_party_sub_ledger'],  //Add Pavel-07-07-22
                            'p_vendor_id' => $line['ap_dist_vendor_id'],  //Add Pavel-07-07-22
                            'p_currency_code' => $line['ap_currency'],
                            'p_exchange_rate' => $line['ap_exchange_rate'],
                            /*'p_amount_type_id' => LTransAmtType::GENERAL_OTHERS,*/
                            'p_amount_ccy' => $line['ap_amount_ccy'],
                            'p_amount_lcy' => $line['ap_amount_lcy'],
                            'p_additional_acc_yn' => 'N',
                            /*'p_dr_cr' => '',*/
                            /*'p_reversal_trans_flag' => '',
                            'p_reversal_ref_trans_date' => '',
                            'p_reversal_ref_invoice_id' => '',
                            'p_reversal_ref_invoice_line_id' => '',
                            'p_authorize_status_id' => '',
                            'p_authorize_by' => '',
                            'p_authorize_date' => '',*/
                            'p_user_id' => auth()->id(),
                            'o_status_code' => &${'status_code' . $key},
                            'o_status_message' => &${'status_message' . $key}
                        ];

                        DB::executeProcedure('CPAACC.fas_ap_trans.trans_ap_invoice_lines', ${'params' . $key});

                        if (${'status_code' . $key} != "1") {
                            DB::rollBack();
                            //throw new \Exception(${"status_message" . $key}, ${'params' . $key});
                            return response()->json(["response_code" => ${"status_code" . $key}, "response_msg" => ${"status_message" . $key} . "Distribution"]);
                        }
                    }
                }

                //dd($request,is_null($request->file('attachment')), $request->file());
                if (!is_null($request->file('attachment'))) {
                    $files = $request->file('attachment');
                    $descriptions = $request->post('attachment');
                    foreach ($files as $key => $file) {
                        if ($file['file'] && $descriptions[$key]['actionType'] == "I") {
                            $byteCode = base64_encode(file_get_contents($file['file']->getRealPath()));
                            $fileExt = $file['file']->extension();
                            $fileName = $file['file']->getClientOriginalName();

                            $fileId = "";
                            ${"file_status_code" . $key} = sprintf("%4000s", "");
                            ${"file_status_message" . $key} = sprintf("%4000s", "");

                            ${"file_params" . $key} = [
                                //'p_action_type' => $actionType,
                                'p_invoice_id' => $invoice_id,
                                /*'p_doc_file_id' => [
                                    "value" => &$fileId,
                                    "type" => \PDO::PARAM_INPUT_OUTPUT,
                                    "length" => 256
                                ],*/
                                'p_doc_file_name' => $fileName,
                                'p_doc_file_name_bng' => "",
                                'p_doc_file_desc' => $descriptions[$key]["description"],
                                'p_doc_file_type' => $fileExt,
                                'p_doc_file_content' => [
                                    "value" => $byteCode,
                                    "type" => SQLT_CLOB
                                ],
                                'p_user_id' => auth()->id(),
                                'o_status_code' => &${"file_status_code" . $key},
                                'o_status_message' => &${"file_status_message" . $key}
                            ];
                            DB::executeProcedure('CPAACC.fas_ap_trans.trans_ap_invoice_docs', ${"file_params" . $key});

                            if (${"file_status_code" . $key} != "1") {
                                DB::rollBack();
                                return response()->json(["response_code" => ${"file_status_code" . $key}, "response_msg" => ${"file_status_message" . $key}]);
                            }
                        }
                    }
                }

                if ($invoice_id) {

                    $validate_ap_invoice_status_code = sprintf("%4000s", "");
                    $validate_ap_invoice_status_message = sprintf("%4000s", "");

                    $validateApInvoiceParams = [
                        'p_invoice_id' => $invoice_id,
                        'o_status_code' => &$validate_ap_invoice_status_code,
                        'o_status_message' => &$validate_ap_invoice_status_message,
                    ];

                    DB::executeProcedure('CPAACC.fas_ap_trans.validate_ap_invoice_entry', $validateApInvoiceParams);

                    if ($validateApInvoiceParams['o_status_code'] != 1) {
                        DB::rollBack();
                        return response()->json(["response_code" => $validate_ap_invoice_status_code, "response_msg" => $validate_ap_invoice_status_message]);
                    }

                    $wk_mapping_status_code = sprintf("%4000s", "");
                    $wk_mapping_status_message = sprintf("%4000s", "");

                    $wkMappingParams = [
                        'P_WORKFLOW_MASTER_ID' => WorkFlowMaster::AP_INVOICE_BILL_ENTRY_APPROVAL,
                        'P_REFERENCE_TABLE' => WkReferenceTable::FAS_AP_INVOICE,
                        'P_REFERANCE_KEY' => WkReferenceColumn::INVOICE_ID,
                        'P_REFERANCE_ID' => $invoice_id,
                        'P_TRANS_PERIOD_ID' => $request->post('period'),
                        'P_INSERT_BY' => auth()->id(),
                        'o_status_code' => &$wk_mapping_status_code,
                        'o_status_message' => &$wk_mapping_status_message,
                    ];


                    DB::executeProcedure('CPAACC.WORKFLOW_MAPPING_ENTRY', $wkMappingParams);
                    if ($wkMappingParams['o_status_code'] != 1) {
                        DB::rollBack();
                        // return $wkMappingParams;
                        return response()->json(["response_code" => $wk_mapping_status_code, "response_msg" => $wk_mapping_status_message]);
                    }
                }

                DB::commit();
                ///dd("done");
                //return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);

                return response()->json(["response_code" => "1", "response_msg" => $status_message, "o_batch" => $trans_batch_id, "o_document_no" => $o_document_no, "period" => $params['p_trans_period_id'], "section"=>$params['p_bill_sec_id']]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["response_code" => '99', 'response_msg' => $e->getMessage()]);
        }
    }

    public function preview(Request $request)
    {
        /**0002555: AP TRANSACTION VIEW (BEFORE SAVE)**/

        $additionalLines = null;
        $distributionLines = null;
        $responses = null;
        $data = null;
        try {
            $responses = DB::select('select * from table (cpaacc.fas_ap_trans.trans_ap_invoice_view(
                                        :p_gl_subsidiary_id,:p_invoice_type_id,
                                        :p_vendor_id,:p_invoice_amount_lcy,
                                        :p_tax_amount_lcy,:p_vat_amount_lcy,
                                        :p_security_deposit_lcy,:p_extra_security_deposit_lcy,
                                        :p_payable_amount_lcy))',
                [
                    'p_gl_subsidiary_id' => (int)$request->post('ap_party_sub_ledger')
                    , 'p_invoice_type_id' => (int)$request->post('ap_invoice_type')
                    , 'p_vendor_id' => (int)$request->post('ap_vendor_id')
                    , 'p_invoice_amount_lcy' => (double)$request->post('ap_invoice_amount_lcy')
                    , 'p_tax_amount_lcy' => (double)$request->post('ap_tax_amount_lcy')
                    , 'p_vat_amount_lcy' => (double)$request->post('ap_vat_amount_lcy')
                    , 'p_security_deposit_lcy' => (double)$request->post('ap_security_deposit_amount_lcy')
                    , 'p_extra_security_deposit_lcy' => (double)$request->post('ap_extra_security_deposit_amount_lcy')
                    , 'p_payable_amount_lcy' => (double)$request->post('ap_payable_amount_lcy')
                ]);
            $voucher = LApInvoiceType::select('INVOICE_TYPE_NAME')->where('invoice_type_id',$request->post('ap_invoice_type'))->first();
            $department = LCostCenterDept::select('COST_CENTER_DEPT_NAME')->where('COST_CENTER_DEPT_ID',$request->post('department'))->first();
            $register = LBillRegister::select('BILL_REG_NAME')->where('BILL_REG_ID',$request->post('bill_register'))->first();
            $section = LBillSection::select('BILL_SEC_NAME')->where('BILL_SEC_ID',$request->post('bill_section'))->first();
            $vendor = FasApVendors::select('VENDOR_NAME')->where('VENDOR_ID',$request->post('ap_vendor_id'))->first();
            $head = FasBudgetHead::select('BUDGET_HEAD_NAME')->where('BUDGET_HEAD_ID',$request->post('b_head_id'))->first();
            $data =[
                'voucher_type'=> isset($voucher) ? $voucher->invoice_type_name : ''
                ,'posting_date'=>HelperClass::dateConvert($request->post('posting_date'))
                ,'document_date'=>HelperClass::dateConvert($request->post('document_date'))
                ,'document_no'=>$request->post('document_number')
                ,'document_reference'=>$request->post('document_reference')
                ,'department'=> isset($department) ? $department->cost_center_dept_name : ''
                ,'register'=> isset($register) ? $register->bill_reg_name : ''
                ,'section'=> isset($section) ? $section->bill_sec_name : ''
                ,'party_id'=> $request->post('ap_vendor_id')
                ,'party_name'=> isset($vendor) ? $vendor->vendor_name : ''
                ,'budget'=> isset($head) ? $head->budget_head_name : ''
                ,'narration'=> $request->post('narration')
            ];

            $additionalLines = $request->post('addLine',null);  /**Always Credit side**/
            $distributionLines = $request->post('line',null);   /**Always Debit side**/
            return response()->json(['response_code'=>1,'response_msg'=>'success','table_content'=>view('ap.ap-common.transaction_dr_cr',compact('responses','additionalLines','distributionLines','data'))->render()]);
        }catch (\Exception $e){
            return response()->json(['response_code'=>99,'response_msg'=>$e->getMessage(),'table_content'=>'']);
        }
    }
}
