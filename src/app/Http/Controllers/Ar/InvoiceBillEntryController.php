<?php
/**
 *Created by PhpStorm
 *Created at ১৩/৯/২১ ৫:০৮ PM
 */

namespace App\Http\Controllers\Ar;


use App\Contracts\Ap\ApLookupContract;
use App\Contracts\Gl\GlContract;
use App\Contracts\LookupContract;
use App\Entities\Common\LCurrency;
use App\Entities\Gl\GlCoa;
use App\Entities\Gl\GlCoaParams;
use App\Enums\Ap\ApFunType;
use App\Enums\Ar\ArFunType;
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
use App\Enums\YesNoFlag;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Ap\ApLookupManager;
use App\Managers\Ar\ArLookupManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceBillEntryController extends Controller
{
    private $lookupManager;
    private $glManager;
    private $glCoa;
    private $glCoaParam;
    private $currency;

    /** @var ApLookupManager */
    private $apLookupManager;
    private $arLookupManager;

    public function __construct(LookupContract $lookupManager,
                                GlContract $glManager,
                                ApLookupContract $apLookupManager,
                                ArLookupManager $arLookupManager)
    {
        $this->lookupManager = $lookupManager;
        $this->glManager = $glManager;
        $this->glCoa = new GlCoa();
        $this->glCoaParam = new GlCoaParams();
        $this->currency = new LCurrency();
        $this->apLookupManager = $apLookupManager;
        $this->arLookupManager = $arLookupManager;
    }

    public function index()
    {
        $user_id = auth()->id();
        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        //$postingDate = $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id);
        $department = $this->lookupManager->getDeptCostCenter();
        $billSecs = $this->lookupManager->getBillSections(ArFunType::AR_INVOICE_BILL_ENTRY);
        //$billRegs = $this->lookupManager->getBillRegisterOnFunction(ArFunType::AR_INVOICE_BILL_ENTRY);

        $vendors = $this->arLookupManager->getCustomers();
        $transactionType = $this->arLookupManager->getTransactionType();
        $customerCategory = $this->arLookupManager->getCustomerCategory();
        //$data['subsidiary_type'] = $this->lookupManager->findArPartySubLedger(LGlInteModules::ACCOUNT_RECEIVABLE);
        $data['subsidiary_type'] = $this->arLookupManager->getArPartySubLedger(ArFunType::AR_INVOICE_BILL_ENTRY); //Add Party Sub-Ledger function wise :pavel-06-04-2022
        $data['currency'] = $this->currency->get();
        $coaParams = $this->lookupManager->getSpecifiedGlCoaParams([\App\Enums\Common\GlCoaParams::INCOME]);
        $paymentTerms = $this->lookupManager->getArPaymentTerms();
        $paymentMethod = $this->lookupManager->getArPaymentMethods();
        $lastPostingBatch = $this->lookupManager->findLastPostingBatchId(LGlInteModules::ACCOUNT_RECEIVABLE, ArFunType::AR_INVOICE_BILL_RECEIPT, $user_id);
        $isRequired = [
            'document_required' => (DB::selectOne("select fas_policy.get_live_deploy_policy_flag from dual")->get_live_deploy_policy_flag == 1 ? 'readonly' : 'required' )
        ];
        return view('ar.invoice-bill-entry.index', compact('isRequired','vendors', 'paymentMethod', 'paymentTerms', 'fiscalYear', 'department','billSecs', 'data', 'coaParams', 'customerCategory', 'lastPostingBatch', 'transactionType'));
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
                'p_module_id' => LGlInteModules::ACCOUNT_RECEIVABLE,
                'p_function_id' => ArFunType::AR_INVOICE_BILL_ENTRY,
                'p_gl_subsidiary_id' => $request->post('ar_party_sub_ledger'),
                'p_transaction_type_id' => $request->post('ar_transaction_type'),
                'p_customer_id' => $request->post('ar_customer_id'),
                'p_trans_period_id' => $request->post('period'),
                'p_trans_date' => HelperClass::dateFormatForDB($request->post('posting_date')),
                'p_document_date' => HelperClass::dateFormatForDB($request->post('document_date')),
                'p_document_no' => $request->post('document_number'),
                'p_document_ref' => $request->post('document_reference'),
                'p_narration' => $request->post('narration'),
                'p_department_id' => $request->post('department'),
                'p_bill_sec_id' => $request->post('bill_section'),
                'p_bill_reg_id' => $request->post('bill_register'),
                'p_currency_code' => $request->post('ar_payment_currency'),
                'p_exchange_rate' => $request->post('ar_exchange_rate'),
                'p_invoice_amount_ccy' => ($request->post('ar_invoice_amount_ccy') != "") ? $request->post('ar_invoice_amount_ccy') : 0,
                'p_invoice_amount_lcy' => ($request->post('ar_invoice_amount_lcy') != "") ? $request->post('ar_invoice_amount_lcy') : 0,
                'p_vat_amount_ccy' => ($request->post('ar_vat_amount_ccy') != "") ? $request->post('ar_vat_amount_ccy') : 0,
                'p_vat_amount_lcy' => ($request->post('ar_vat_amount_lcy') != "") ? $request->post('ar_vat_amount_lcy') : 0,
                'p_receivable_amount_ccy' => ($request->post('ar_receivable_amount_ccy') != "") ? $request->post('ar_receivable_amount_ccy') : 0,
                'p_receivable_amount_lcy' => ($request->post('ar_receivable_amount_lcy') != "") ? $request->post('ar_receivable_amount_lcy') : 0,
                'p_receipt_terms_id' => $request->post('ar_payment_terms'),
                'p_receipt_due_date' => HelperClass::dateFormatForDB($request->post('ar_payment_due_date')),
                'p_receipt_methods_id' => $request->post('ar_payment_method'),
                'p_user_id' => auth()->id(),
                'p_system_generated_yn' => YesNoFlag::NO,
                'o_invoice_id' => [
                    'value' => &$invoice_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'o_trans_batch_id' => [
                    'value' => &$trans_batch_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'o_document_no' => &$o_document_no,
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message
            ];

            DB::executeProcedure('CPAACC.fas_ar_trans.trans_ar_invoice_entry', $params);

            if ($params['o_status_code'] != "1") {
                DB::rollBack();
                return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
            } else {
                $lines = $request->post('line');
                foreach ($lines as $key => $line) {
                    ${'status_code' . $key} = sprintf("%4000s", "");
                    ${'status_message' . $key} = sprintf("%4000s", "");
                    $invoice_line_id = '';

                    ${'params' . $key} = [
                        'p_invoice_id' => $invoice_id,
                        'p_module_id' => LGlInteModules::ACCOUNT_RECEIVABLE,
                        'p_function_id' => ArFunType::AR_INVOICE_BILL_ENTRY,
                        'p_trans_period_id' => (integer)$request->post('period'),
                        'p_trans_date' => HelperClass::dateFormatForDB($request->post('posting_date')),
                        'p_document_date' => HelperClass::dateFormatForDB($request->post('document_date')),
                        'p_document_no' => $request->post('document_number'),
                        'p_document_ref' => $request->post('document_reference'),
                        'p_narration' => $request->post('narration'),
                        'p_department_id' => (integer)$request->post('department'),
                        'p_bill_sec_id' => (integer)$request->post('bill_section'),
                        'p_bill_reg_id' => (integer)$request->post('bill_register'),
                        'p_gl_acc_id' => (integer)$line['ar_account_code'],
                        'p_currency_code' => $line['ar_currency'],
                        'p_exchange_rate' => $line['ar_exchange_rate'],
                        'p_amount_ccy' => $line['ar_amount_ccy'],
                        'p_amount_lcy' => $line['ar_amount_lcy'],
                        'p_user_id' => auth()->id(),
                        'o_status_code' => &${'status_code' . $key},
                        'o_status_message' => &${'status_message' . $key}
                    ];
                    DB::executeProcedure('CPAACC.fas_ar_trans.trans_ar_invoice_lines', ${'params' . $key});

                    if (${'status_code' . $key} != "1") {
                        DB::rollBack();
                        return response()->json(["response_code" => ${"status_code" . $key}, "response_msg" => ${"status_message" . $key}]);
                    }
                }
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
                                'p_invoice_id' => $invoice_id,
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
                            DB::executeProcedure('CPAACC.fas_ar_trans.trans_ar_invoice_docs', ${"file_params" . $key});

                            if (${"file_status_code" . $key} != "1") {
                                DB::rollBack();
                                return response()->json(["response_code" => ${"file_status_code" . $key}, "response_msg" => ${"file_status_message" . $key}]);
                            }
                        }
                    }
                }

                if ($invoice_id) {
                    $validate_ar_invoice_status_code = sprintf("%4000s", "");
                    $validate_ar_invoice_status_message = sprintf("%4000s", "");

                    $validateArInvoiceParams = [
                        'p_invoice_id' => $invoice_id,
                        'o_status_code' => &$validate_ar_invoice_status_code,
                        'o_status_message' => &$validate_ar_invoice_status_message,
                    ];

                    DB::executeProcedure('CPAACC.fas_ar_trans.validate_ar_invoice_entry', $validateArInvoiceParams);

                    if ($validateArInvoiceParams['o_status_code'] != 1) {
                        DB::rollBack();
                        return response()->json(["response_code" => $validate_ar_invoice_status_code, "response_msg" => $validate_ar_invoice_status_message]);
                    }

                    $wk_mapping_status_code = sprintf("%4000s", "");
                    $wk_mapping_status_message = sprintf("%4000s", "");

                    $wkMappingParams = [
                        'P_WORKFLOW_MASTER_ID' => WorkFlowMaster::AR_INVOICE_BILL_ENTRY_APPROVAL,
                        'P_REFERENCE_TABLE' => WkReferenceTable::FAS_AR_INVOICE,
                        'P_REFERANCE_KEY' => WkReferenceColumn::AR_INVOICE_ID,
                        'P_REFERANCE_ID' => $invoice_id,
                        'P_TRANS_PERIOD_ID' => $request->post('period'),
                        'P_INSERT_BY' => auth()->id(),
                        'o_status_code' => &$wk_mapping_status_code,
                        'o_status_message' => &$wk_mapping_status_message,
                    ];


                    DB::executeProcedure('CPAACC.WORKFLOW_MAPPING_ENTRY', $wkMappingParams);
                    if ($wkMappingParams['o_status_code'] != 1) {
                        DB::rollBack();
                        return $wkMappingParams;
                    }
                }

                DB::commit();
                return response()->json(["response_code" => $status_code, "response_msg" => $status_message, "o_batch" => $trans_batch_id, "o_document_no" => $o_document_no, "period" =>$params['p_trans_period_id'] ]);

            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["response_code" => '99', 'response_msg' => $e->getMessage()]);
        }
    }
}
