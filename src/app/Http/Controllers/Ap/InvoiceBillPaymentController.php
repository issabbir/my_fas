<?php


namespace App\Http\Controllers\Ap;

use App\Contracts\Ap\ApContract;
use App\Contracts\Ap\ApLookupContract;
use App\Contracts\LookupContract;
use App\Entities\Ap\FasApInvoiceDoc;
use App\Entities\Ap\FasApPaymentDocs;
use App\Entities\Cm\CmBankBranch;
use App\Entities\WorkFlowMapping;
use App\Entities\WorkFlowTemplate;
use App\Enums\Ap\ApChequePaymentType;
use App\Enums\Ap\ApFunType;
use App\Enums\Common\GlSubsidiaryParams;
use App\Enums\Common\LGlInteFun;
use App\Enums\Common\LGlInteModules;
use App\Enums\Common\LGlSubsidiaryType;
use App\Enums\Gl\FunctionTypes;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Enums\WorkFlowRoleKey;
use App\Enums\YesNoFlag;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Ap\ApLookupManager;
use App\Managers\Ap\ApManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use App\Traits\Security\HasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceBillPaymentController extends Controller
{
    use HasPermission;

    /** @var LookupManager */
    private $lookupManager;

    /** @var ApLookupManager */
    private $apLookupManager;

    /** @var ApManager */
    private $apManager;

    public function __construct(LookupContract $lookupManager, ApLookupContract $apLookupManager, ApContract $apManager)
    {
        $this->lookupManager = $lookupManager;
        $this->apLookupManager = $apLookupManager;
        $this->apManager = $apManager;
    }

    public function index()
    {
        $user_id = auth()->id();
        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        return view('ap.invoice-bill-payment.index', [
            'department' => $this->lookupManager->getDeptCostCenter(),
            //'postPeriodList' => $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id),
            'fiscalYear' =>$fiscalYear,
            'lBillSecList' => $this->lookupManager->getBillSections(ApFunType::AP_INVOICE_BILL_PAYMENT),
            //'billRegs' => $this->lookupManager->getBillRegisterOnFunction(ApFunType::AP_INVOICE_BILL_PAYMENT),
            'vendorType' => $this->apLookupManager->getVendorTypes(),
            'vendorCategory' => $this->apLookupManager->getVendorCategory(),
            'bankAccList' => $this->apLookupManager->getApBankAcc(), //Add this part :pavel-11-05-2022
            //'bankAccList' => $this->apLookupManager->getBankAcc(), //Block part :pavel-11-05-2022
            //'partySubLedgerList' => $this->lookupManager->findPartySubLedger(LGlInteModules::ACC_PAY_VENDOR, LGlSubsidiaryType::ACCOUNTS_PAYABLE),
            //'partySubLedgerList' => $this->lookupManager->findIncVatTaxAcPayPartySubLedger(LGlInteModules::ACC_PAY_VENDOR),
            'partySubLedgerList' => $this->apLookupManager->getPartySubLedger(ApFunType::AP_INVOICE_BILL_PAYMENT), //Add Party Sub-Ledger function wise :pavel-06-04-2022
            'lastPostingBatch' => $this->lookupManager->findLastPostingBatchId(LGlInteModules::ACC_PAY_VENDOR, ApFunType::AP_INVOICE_BILL_PAYMENT, $user_id),
            'pmis_bills_name' => $this->lookupManager->getPmisBills(),
            'misc' => [
                'document_required' => (DB::selectOne("select fas_policy.get_live_deploy_policy_flag from dual")->get_live_deploy_policy_flag == 1 ? 'readonly' : 'required' ),
                'draft_area' => (DB::selectOne("select fas_policy.get_live_deploy_policy_flag from dual")->get_live_deploy_policy_flag == 1 ? '' : 'd-none' ),
                'is_live' =>(DB::selectOne("select fas_policy.get_live_deploy_policy_flag from dual")->get_live_deploy_policy_flag == 1 ? '1' : '0' )
            ]
        ]);
    }

    public function paymentQueueLists(Request $request)
    {
        $postData = $request->post();
        $intBillPayYn = isset($postData['internal_bill_pay_yn']) && ($postData['internal_bill_pay_yn'] == YesNoFlag::YES) ? YesNoFlag::YES : YesNoFlag::NO;
        $queryResult = [];

        if (empty($postData['party_sub_ledger_id'])) {
            $queryResult = [];
        } else {
            $sql = "select cpaacc.fas_ap_trans.get_ap_bill_payment_queue (:p_gl_subsidiary_id,:p_internal_bill_pmt_yn) from dual";
            $queryResult = DB::select(DB::raw($sql), ['p_gl_subsidiary_id'=> $postData['party_sub_ledger_id'], 'p_internal_bill_pmt_yn'=> $intBillPayYn ]);
        }

        return datatables()->of($queryResult)
            ->addColumn('select', function ($query) {
                return '<button class="btn btn-primary btn-sm payment-queue-inv"  id="'.$query->invoice_id.'" data-vendor="'.$query->vendor_id.'" >Select</button>';
            })
            ->rawColumns(['select'])
            ->addIndexColumn()
            ->make(true);
    }

    public function taxPaymentQueueLists()
    {
        $sql = "select cpaacc.fas_ap_trans.get_ap_tax_payment_queue from dual";
        $queryResult = DB::select(DB::raw($sql));

        return datatables()->of($queryResult)
            ->addColumn('select', function ($query) {
                return '<button class="btn btn-primary btn-sm tax-pay-queue-inv" id="'.$query->invoice_id.'" >Select</button>';
            })
            ->rawColumns(['select'])
            ->addIndexColumn()
            ->make(true);
    }

    public function view(Request $request, $id,$filter=null)
    {
        $invBillPayInfo = DB::selectOne("select cpaacc.fas_ap_trans.get_ap_payment_view (:p_payment_id) from dual", ['p_payment_id' => $id]);

        //$invReferenceList = DB::select("select CPAACC.fas_ap_trans.get_ap_bill_payment_ref_vw (:p_gl_subsidiary_id, :p_payment_id) from dual", ['p_gl_subsidiary_id' => $invBillPayInfo->gl_subsidiary_id, 'p_payment_id' => $id]);
        $invReferenceList = DB::select("select CPAACC.fas_ap_trans.get_ap_bill_payment_ref_vw (:p_payment_id) from dual", ['p_payment_id' => $id]);
        $invRefTaxPayList = DB::select("select CPAACC.fas_ap_trans.get_ap_tax_payment_ref_vw (:p_payment_id) from dual", ['p_payment_id' => $id]);

        $invPaymentDocsList = FasApPaymentDocs::where('payment_id', $id)->get();
        $invoice_line = DB::select("select cpaacc.fas_ap_trans.get_ap_payment_trans_view(:p_payment_id) from dual", ["p_payment_id" => $id]);

        $user_id = auth()->id();
        //$fiscalYear = $this->lookupManager->getACurrentFinancialYear();
        //$roleWiseUser = $this->apManager->findRoleWiseUser(WorkFlowMaster::AP_INVOICE_BILL_PAYMENT_APPROVAL, WorkFlowRoleKey::AP_INVOICE_BILL_PAYMENT_MAKE, $user_id);  //Add & call this function  :pavel-29-05-2022
        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();

        return view('ap.invoice-bill-payment.view', [
            'invBillPayInfo' => $invBillPayInfo,
            'invReferenceList' => $invReferenceList,
            'invRefTaxPayList' => $invRefTaxPayList,
            'invPaymentDocsList' => $invPaymentDocsList,
            'invoice_line' => $invoice_line,
            'fiscalYear' => $fiscalYear,
            //'roleWiseUser' => $roleWiseUser,

            'department' => $this->lookupManager->getDeptCostCenter(),
            /*'postPeriodList' => $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id),*/
            'lBillSecList' => $this->lookupManager->getBillSections(ApFunType::AP_INVOICE_BILL_PAYMENT),
            'vendorType' => $this->apLookupManager->getVendorTypes(),
            'vendorCategory' => $this->apLookupManager->getVendorCategory(),
            'bankAccList' => $this->apLookupManager->getBankAcc(),
            //'partySubLedgerList' => $this->lookupManager->findPartySubLedger(LGlInteModules::ACC_PAY_VENDOR, LGlSubsidiaryType::ACCOUNTS_PAYABLE),
            //'partySubLedgerList' => $this->lookupManager->findIncVatTaxAcPayPartySubLedger(LGlInteModules::ACC_PAY_VENDOR),
            'partySubLedgerList' => $this->apLookupManager->getPartySubLedger(ApFunType::AP_INVOICE_BILL_PAYMENT), //Add Party Sub-Ledger function wise :pavel-06-04-2022
            'lastPostingBatch' => $this->lookupManager->findLastPostingBatchId(LGlInteModules::ACC_PAY_VENDOR, ApFunType::AP_INVOICE_BILL_PAYMENT, $user_id),
            'filter' =>$filter
        ]);
    }

    /*public function store(Request $request) {
        $response = $this->inv_bill_pay_api_ins($request);

        $message = $response['o_status_message'];

        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', $message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('invoice-bill-payment.index');
    }*/

    //private function inv_bill_pay_api_ins(Request $request)
    /*****Added on: 25082022****/

    public function makeDraft(Request $request)
    {
        $invoiceReferences = $request->post('referenceList');
        $documentNo = $request->post('documentNo');
        $documentDate = $request->post('documentDate');
        $narration = $request->post('narration');

        DB::beginTransaction();
        $status_code = sprintf("%4000s","");
        $status_message = sprintf("%4000s","");

        $invRefParams = [
            'p_action_type' => 'D',
            'p_document_no' => $documentNo,
            'p_document_date' => HelperClass::dateFormatForDB($documentDate),
            'p_invoice_id' => null,
            'p_payment_amt' => null,
            'p_narration' => $narration,
            'p_order_sl_no' => "",
            'o_status_code' => &$status_code,
            'o_status_message' => &$status_message
        ];
        //dd($invRefParams);
        DB::executeProcedure('cpaacc.fas_ap_trans.trans_ap_payment_invoices_draft', $invRefParams);

        foreach ($invoiceReferences as $invoiceReference) {
            $invRefParams = [];
            $inv_ref_status_code = sprintf("%4000s", "");
            $inv_ref_status_message = sprintf("%4000s", "");
            $invRefParams = [
                'p_action_type' => 'I',
                'p_document_no' => $documentNo,
                'p_document_date' => HelperClass::dateFormatForDB($documentDate),
                'p_invoice_id' => $invoiceReference['invoiceId'],
                'p_payment_amt' => $invoiceReference['paymentAmount'],
                'p_narration' => $narration,
                'p_order_sl_no' => $invoiceReference['order'],
                'o_status_code' => &$inv_ref_status_code,
                'o_status_message' => &$inv_ref_status_message
            ];
            DB::executeProcedure('cpaacc.fas_ap_trans.trans_ap_payment_invoices_draft', $invRefParams);
            if ($inv_ref_status_code != 1) {
                DB::rollBack();
                return response()->json(["response_code" => $inv_ref_status_code, "response_msg" => $inv_ref_status_message]);
            }
        }
        DB::commit();
        return response()->json(["response_code" => $inv_ref_status_code, "response_msg" => $inv_ref_status_message]);

    }

    public function store(Request $request)
    {
        $postData = $request->post();
        $invoiceReferences = isset($postData['invoice_reference']) ? $postData['invoice_reference'] : [];
        $invoiceReferencesCashChq = isset($postData['invoice_reference_cash_cheque']) ? $postData['invoice_reference_cash_cheque'] : [];
        $invoiceReferencesTax = isset($postData['invoice_reference_tax']) ? $postData['invoice_reference_tax'] : [];
        $files = $request->file('attachment') ? $request->file('attachment') : [];
        //$files = $request->files;

        try {
            DB::beginTransaction();

            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            //$payment_id = sprintf("%4000s","");
            $trans_batch_id = sprintf("%4000s", "");
            $o_document_no = sprintf("%4000s", "");
            $payment_id = '';

            $params = [
                'p_module_id' => LGlInteModules::ACC_PAY_VENDOR,
                'p_function_id' => ApFunType::AP_INVOICE_BILL_PAYMENT,
                'p_gl_subsidiary_id' => isset($postData['party_sub_ledger']) ? $postData['party_sub_ledger'] : '',
                'p_vendor_id' => isset($postData['vendor_id']) ? $postData['vendor_id'] : '',
                'p_emp_id' => null,
                'p_trans_period_id' => $postData['period'],
                'p_trans_date' => HelperClass::dateFormatForDB($postData['posting_date']),

                'p_document_date' => HelperClass::dateFormatForDB($postData['document_date']),
                'p_document_no' => $postData['document_number'],
                'p_document_ref' => isset($postData['document_reference']) ? $postData['document_reference'] : '',
                'p_narration' => $postData['narration'],
                'p_department_id' => $postData['department'],
                'p_bill_sec_id' => $postData['bill_sec_id'],
                'p_bill_reg_id' => $postData['bill_reg_id'],
                'p_bank_account_id' => $postData['bank_id'],
                'p_cheque_no' => isset ($postData['cheque_no']) ? $postData['cheque_no'] : null,
                'p_cheque_date' => isset ($postData['cheque_date']) ? (HelperClass::dateFormatForDB($postData['cheque_date'])) : null,
                'p_cheque_pmt_type_flag' => isset ($postData['cheque_pay_type_id']) && ($postData['cheque_pay_type_id'] == ApChequePaymentType::CASH_CHEQUE) ? $postData['cheque_pay_type_id'] : ApChequePaymentType::ACCOUNT_PAYEE_CHEQUE,
                'p_internal_bill_pmt_yn' => isset($postData['internal_bill_pay_yn']) && ($postData['internal_bill_pay_yn'] == YesNoFlag::YES) ? YesNoFlag::YES : YesNoFlag::NO,
                'p_currency_code' => $postData['currency'],
                'p_exchange_rate' => $postData['exc_rate'],
                'p_payment_amount_ccy' => isset ($postData['bank_pay_amt_ccy']) ? $postData['bank_pay_amt_ccy'] : 0,
                'p_payment_amount_lcy' => $postData['bank_pay_amt_lcy'],
                'p_adjust_prepayments_ccy' => isset($postData['adj_pre_pay_amt_ccy']) ? $postData['adj_pre_pay_amt_ccy'] : 0,
                'p_adjust_prepayments_lcy' => $postData['adj_pre_pay_amt_lcy'],
                'p_fine_forfeiture_ccy' => isset($postData['fine_forfeiture_ccy']) ? $postData['fine_forfeiture_ccy'] : 0,
                'p_fine_forfeiture_lcy' => $postData['fine_forfeiture_lcy'],
                //'p_tax_invoice_id' => isset($postData['selected_tax_pay_queue_inv_id']) ? $postData['selected_tax_pay_queue_inv_id'] : null,
                'p_user_id' => auth()->id(),
                'p_system_generated_yn' => YesNoFlag::NO,
                //'o_payment_id' => &$payment_id,
                'p_favoring' => isset ($postData['favoring']) ? $postData['favoring'] : null,
                "o_payment_id" => [
                    'value' => &$payment_id,
                    'type' => \PDO::PARAM_INT,
                    'length' => 255
                ],
                'o_trans_batch_id' => &$trans_batch_id,
                'o_document_no' => &$o_document_no,
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];

            DB::executeProcedure('cpaacc.fas_ap_trans.trans_ap_payment_entry', $params);
            $master_params = $params;
            /*return response()->json(["response_code" => $status_code, 'response_msg' => $status_message]);
            die();*/

            $oPaymentId = isset($params['o_payment_id']['value']) ? $params['o_payment_id']['value'] : '';

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                //return $params;
                return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
            }

            if ((count($files) > 0) && $oPaymentId) {
                $invDescription = isset($postData['attachment']) ? $postData['attachment'] : [];
                foreach ($files as $key => $paymentFile) {
                    $fileName = '';
                    $fileType = '';
                    $fileContent = '';
                    $file = $paymentFile['file'];

                    if ($file) {
                        $fileName = $file->getClientOriginalName();
                        $fileType = $file->getMimeType();
                        $fileContent = base64_encode(file_get_contents($file->getRealPath()));
                    }

                    $pay_file_status_code = sprintf("%4000s", "");
                    $pay_file_status_message = sprintf("%4000s", "");

                    $payFileParams = [
                        'p_payment_id' => $oPaymentId,
                        'p_doc_file_name' => $fileName,
                        'p_doc_file_name_bng' => null,
                        'p_doc_file_desc' => $invDescription[$key]["description"],
                        'p_doc_file_type' => $fileType,
                        'p_doc_file_content' => [
                            'value' => $fileContent,
                            'type' => SQLT_CLOB,
                        ],
                        'p_user_id' => auth()->id(),
                        'o_status_code' => &$pay_file_status_code,
                        'o_status_message' => &$pay_file_status_message,
                    ];

                    //echo ($fileName.'=='.$fileType.'=='.$invDescription[$key]["description"].'<br>');
                    DB::executeProcedure('cpaacc.fas_ap_trans.trans_ap_payment_docs', $payFileParams);

                    $params = $payFileParams; // Last one should be assigned and return back!

                    if ($payFileParams['o_status_code'] != 1) {
                        DB::rollBack();
                        //return $payFileParams;
                        return response()->json(["response_code" => $pay_file_status_code, "response_msg" => $pay_file_status_message]);
                    }
                }
                //die();
            }

            if ($postData['party_sub_ledger'] != GlSubsidiaryParams::VAT_PAYABLE) {

                //insert into Invoice Reference
                if ((count($invoiceReferences) > 0) && $oPaymentId) {

                    foreach ($invoiceReferences as $invoiceReference) {
                        $invRefParams = [];
                        $inv_ref_status_code = sprintf("%4000s", "");
                        $inv_ref_status_message = sprintf("%4000s", "");
                        $invRefParams = [
                            'p_payment_id' => $oPaymentId,
                            'p_invoice_id' => $invoiceReference['inv_ref_check'],
                            'p_payment_amt' => $invoiceReference['inv_ref_pay_amt'],
                            'p_order_sl_no' => $invoiceReference['supply_order'],
                            'p_user_id' => Auth()->ID(),
                            'o_status_code' => &$inv_ref_status_code,
                            'o_status_message' => &$inv_ref_status_message
                        ];

                        DB::executeProcedure('cpaacc.fas_ap_trans.trans_ap_payment_invoices', $invRefParams);

                        $params = $invRefParams; // Last one should be assigned and return back!

                        if ($invRefParams['o_status_code'] != 1) {
                            DB::rollBack();
                            //return $invRefParams;
                            return response()->json(["response_code" => $inv_ref_status_code, "response_msg" => $inv_ref_status_message]);
                        }
                        //echo ($oPaymentId.'=='.$invoiceReference['inv_ref_check'].'=='.'<br>');
                    }
                    //die();
                }

                //insert into Invoice Reference Cash Cheque
                if ((count($invoiceReferencesCashChq) > 0) && $oPaymentId) {

                    foreach ($invoiceReferencesCashChq as $invoiceReferenceCashChq) {
                        $invRefCashChqParams = [];
                        $inv_ref_cash_chq_status_code = sprintf("%4000s", "");
                        $inv_ref_cash_chq_status_message = sprintf("%4000s", "");
                        $invRefCashChqParams = [
                            'p_payment_id' => $oPaymentId,
                            'p_invoice_id' => $invoiceReferenceCashChq['inv_ref_cash_chq_check'],
                            'p_payment_amt' => $invoiceReferenceCashChq['inv_ref_cash_cheque_pay_amt'],
                            'p_order_sl_no' => $invoiceReferenceCashChq['cash_chq_check_order'],
                            'p_user_id' => Auth()->ID(),
                            'o_status_code' => &$inv_ref_cash_chq_status_code,
                            'o_status_message' => &$inv_ref_cash_chq_status_message
                        ];

                        DB::executeProcedure('cpaacc.fas_ap_trans.trans_ap_payment_invoices', $invRefCashChqParams);

                        $params = $invRefCashChqParams; // Last one should be assigned and return back!

                        if ($invRefCashChqParams['o_status_code'] != 1) {
                            DB::rollBack();
                            //return $invRefParams;
                            return response()->json(["response_code" => $inv_ref_cash_chq_status_code, "response_msg" => $inv_ref_cash_chq_status_message]);
                        }
                    }
                }

                //insert into Invoice Reference Tax
                if ((count($invoiceReferencesTax) > 0) && $oPaymentId) {

                    foreach ($invoiceReferencesTax as $invoiceReferenceTax) {
                        $invRefTaxParams = [];
                        $inv_ref_tax_status_code = sprintf("%4000s", "");
                        $inv_ref_tax_status_message = sprintf("%4000s", "");
                        $invRefTaxParams = [
                            'p_payment_id' => $oPaymentId,
                            'p_invoice_id' => $invoiceReferenceTax['inv_ref_tax_pay_check'],
                            'p_payment_amt' => $invoiceReferenceTax['tax_payment_amt'],
                            'p_order_sl_no' => $invoiceReferenceTax['tax_order'],
                            'p_user_id' => Auth()->ID(),
                            'o_status_code' => &$inv_ref_tax_status_code,
                            'o_status_message' => &$inv_ref_tax_status_message
                        ];

                        DB::executeProcedure('cpaacc.fas_ap_trans.trans_ap_payment_invoices', $invRefTaxParams);

                        $params = $invRefTaxParams; // Last one should be assigned and return back!

                        if ($invRefTaxParams['o_status_code'] != 1) {
                            DB::rollBack();
                            //return $invRefParams;
                            return response()->json(["response_code" => $inv_ref_tax_status_code, "response_msg" => $inv_ref_tax_status_message]);
                        }
                    }
                }
            }

            if ($oPaymentId) {

                $validate_ap_payment_status_code = sprintf("%4000s", "");
                $validate_ap_payment_status_message = sprintf("%4000s", "");

                $validateApPaymentParams = [
                    'p_invoice_id' => $oPaymentId,
                    'o_status_code' => &$validate_ap_payment_status_code,
                    'o_status_message' => &$validate_ap_payment_status_message,
                ];

                DB::executeProcedure('CPAACC.fas_ap_trans.validate_ap_payment_entry', $validateApPaymentParams);

                if ($validateApPaymentParams['o_status_code'] != 1) {
                    DB::rollBack();
                    return response()->json(["response_code" => $validate_ap_payment_status_code, "response_msg" => $validate_ap_payment_status_message]);
                }
            }

            if ($oPaymentId) {
                $wk_mapping_status_code = sprintf("%4000s", "");
                $wk_mapping_status_message = sprintf("%4000s", "");

                $wkMappingParams = [
                    'P_WORKFLOW_MASTER_ID' => WorkFlowMaster::AP_INVOICE_BILL_PAYMENT_APPROVAL,
                    'P_REFERENCE_TABLE' => WkReferenceTable::FAS_AP_PAYMENT,
                    'P_REFERANCE_KEY' => WkReferenceColumn::PAYMENT_ID,
                    'P_REFERANCE_ID' => $oPaymentId,
                    'P_TRANS_PERIOD_ID' => $postData['period'],
                    'P_INSERT_BY' => auth()->id(),
                    'o_status_code' => &$wk_mapping_status_code,
                    'o_status_message' => &$wk_mapping_status_message,
                ];

                DB::executeProcedure('CPAACC.WORKFLOW_MAPPING_ENTRY', $wkMappingParams);
                if ($wkMappingParams['o_status_code'] != 1) {
                    DB::rollBack();
                    //return $wkMappingParams;
                    return response()->json(["response_code" => $wk_mapping_status_code, "response_msg" => $wk_mapping_status_message]);
                }
            }

        } catch (\Exception $e) {

            DB::rollBack();
            //return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
            return response()->json(["response_code" => '99', 'response_msg' => $e->getMessage()]);
        }
        DB::commit();
        //return $params;
        //return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
        return response()->json(["response_code" => $status_code, "response_msg" => $status_message, "o_batch" => $trans_batch_id, "o_document_no" => $o_document_no, "period" =>$master_params['p_trans_period_id'], "cheque_type"=> ($postData['cheque_pay_type_id'] == ApChequePaymentType::CASH_CHEQUE) ? $postData['cheque_pay_type_id'] : ApChequePaymentType::ACCOUNT_PAYEE_CHEQUE ]);

    }

    public function updateInvoiceEntry(Request $request)
    {
        $status_code = sprintf("%4000d","");
        $status_message = sprintf("%4000s","");
        $params = [
            'p_payment_id' => $request->post('paymentId'),
            'p_trans_period_id' => $request->post('postingPeriod'),
            'p_trans_date' => HelperClass::dateFormatForDB($request->post('postingDate')),
            'p_document_date' => HelperClass::dateFormatForDB($request->post('documentDate')),
            'p_document_no' => $request->post('documentNumber'),
            'p_document_ref' => $request->post('documentRef'),
            'p_department_id' => $request->post('department'),
            'p_bill_reg_id' => $request->post('billRegister'),
            'p_bill_sec_id' => $request->post('billSection'),
            'p_narration' => $request->post('documentNarration'),
            'p_cheque_no' => $request->post('chequeNo'),
            'p_cheque_date' => HelperClass::dateFormatForDB($request->post('chequeDate')),
            'p_favoring' => $request->post('favoring') ? $request->post('favoring') : null,
            'p_user_id' => Auth()->id(),
            'o_status_code' => &$status_code,
            'o_status_message' => &$status_message
        ];

        DB::beginTransaction();
        try {
            DB::executeProcedure("CPAACC.fas_ap_trans.trans_ap_payment_ref_update",$params);
            DB::commit();
        }catch (\Exception $e){
            return ['response_code'=>99, 'response_message'=>$e->getMessage()];
            DB::rollBack();
        }
        return ['response_code'=>$status_code, 'response_message'=>$status_message];
    }

}
