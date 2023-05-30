<?php


namespace App\Http\Controllers\Ar;

use App\Contracts\Ar\ArContract;
use App\Contracts\LookupContract;
use App\Entities\Ar\FasArReceiptDocs;
use App\Enums\Ar\ArFunType;
use App\Enums\BudgetMonitoring\BmnFunctionType;
use App\Enums\Common\LGlInteModules;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Enums\WorkFlowRoleKey;
use App\Enums\YesNoFlag;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Ar\ArLookupManager;
use App\Managers\Ar\ArManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use App\Traits\Security\HasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceBillReceiptController extends Controller
{
    use HasPermission;

    /** @var LookupManager */
    private $lookupManager;

    /** @var ArLookupManager */
    private $arLookupManager;

    /** @var ArManager */
    private $arManager;

    public function __construct(LookupContract $lookupManager, ArLookupManager $arLookupManager, ArContract $arManager)
    {
        $this->lookupManager = $lookupManager;
        $this->arLookupManager = $arLookupManager;
        $this->arManager = $arManager;
    }

    public function index()
    {
        $user_id = auth()->id();
        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        return view('ar.invoice-bill-receipt.index', [
            //'postPeriodList' => $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id),
            //'lBillSecList' => $this->lookupManager->findLBillSec(),
            'fiscalYear'=>$fiscalYear,
            'department' => $this->lookupManager->getDeptCostCenter(),
            'billSecs' => $this->lookupManager->getBillSections(ArFunType::AR_INVOICE_BILL_RECEIPT),
            //'billRegs' => $this->lookupManager->getBillRegisterOnFunction(ArFunType::AR_INVOICE_BILL_RECEIPT),

        'customerCategory' => $this->arLookupManager->getCustomerCategory(),
            'receiptMethods' => $this->arLookupManager->getLArReceiptMethods(),
            'bankAccList' => $this->arLookupManager->getArBankAcc(),
            'partySubLedgerList' => $this->lookupManager->findArPartySubLedger(LGlInteModules::ACCOUNT_RECEIVABLE),
            'lastPostingBatch' => $this->lookupManager->findLastPostingBatchId(LGlInteModules::ACCOUNT_RECEIVABLE, ArFunType::AR_INVOICE_BILL_RECEIPT, $user_id),
            'isRequired' => [
                'document_required' => (DB::selectOne("select fas_policy.get_live_deploy_policy_flag from dual")->get_live_deploy_policy_flag == 1 ? 'readonly' : 'required' )
            ]
        ]);
    }

    public function view(Request $request, $id, $filter=null)
    {
        $user_id = auth()->id();
        $invReferenceList = [];

        $invBillReceiptInfo = DB::selectOne("select cpaacc.fas_ar_trans.get_ar_receipt_view (:p_receipt_id) from dual", ['p_receipt_id' => $id]);

        if ($invBillReceiptInfo) {
            //$invReferenceList = DB::select("select CPAACC.fas_ar_trans.get_receipt_invoice_referance (:p_gl_subsidiary_id, :p_receipt_id) from dual", ['p_gl_subsidiary_id' => $invBillReceiptInfo->gl_subsidiary_id, 'p_receipt_id' => $id]);
            $invReferenceList = DB::select("select CPAACC.fas_ar_trans.get_ar_receipt_ref_vw (:p_receipt_id) from dual", ['p_receipt_id' => $id]);
            $invBillReceiptInfo->invoice_line = DB::select("select cpaacc.fas_ar_trans.get_ar_receipt_trans_view(:p_receipt_id) from dual", ["p_receipt_id" => $id]);

        }

        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        //$postingDate = $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id);
        //dd($invBillReceiptInfo);
        $invReceiptDocsList = FasArReceiptDocs::where('receipt_id', $id)->get();
        $department = $this->lookupManager->getDeptCostCenter();
        //$roleWiseUser = $this->arManager->findRoleWiseUser(WorkFlowMaster::AR_INVOICE_BILL_RECEIPT_APPROVAL, WorkFlowRoleKey::AR_INVOICE_BILL_RECEIPT_MAKE, $user_id);  //Add & call this function  :pavel-29-05-2022

        return view('ar.invoice-bill-receipt.view', [
            'invBillReceiptInfo' => $invBillReceiptInfo,
            'invReferenceList' => $invReferenceList,
            'invReceiptDocsList' => $invReceiptDocsList,
            'fiscalYear' => $fiscalYear,
            'department' => $department,
            //'roleWiseUser' => $roleWiseUser,
            'billSecs' => $this->lookupManager->getBillSections(ArFunType::AR_INVOICE_BILL_RECEIPT),
            'filter' =>$filter
        ]);
    }

    public function store(Request $request)
    {
        $postData = $request->post();
        $invoiceReferences = isset($postData['invoice_reference']) ? $postData['invoice_reference'] : [];
        $files = $request->file('attachment') ? $request->file('attachment') : [];
        //$files = $request->files;

        try {
            DB::beginTransaction();

            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            //$receipt_id = sprintf("%4000s","");
            $trans_batch_id = sprintf("%4000s", "");
            $o_document_no = sprintf("%4000s", "");
            $receipt_id = '';

            $params = [
                'p_module_id' => LGlInteModules::ACCOUNT_RECEIVABLE,
                'p_function_id' => ArFunType::AR_INVOICE_BILL_RECEIPT,
                //'p_gl_subsidiary_id' => $postData['party_sub_ledger'],
                'p_customer_id' => $postData['customer_id'],
                'p_trans_period_id' => $postData['period'],
                'p_trans_date' => HelperClass::dateFormatForDB($postData['posting_date']),
                'p_document_date'=> HelperClass::dateFormatForDB($postData['document_date']),
                'p_document_no' => $postData['document_number'],
                'p_document_ref' => isset($postData['document_reference']) ? $postData['document_reference'] : '',
                'p_narration'=> $postData['narration'],
                'p_department_id'=> $postData['department'],
                'p_bill_sec_id'=> $postData['bill_section'],
                'p_bill_reg_id'=> isset($postData['bill_register']) ? $postData['bill_register'] : '',
                'p_bank_account_id' => $postData['bank_id'],
                'p_instrument_type_id' => $postData['receipt_instrument'],
                'p_instrument_amount' => isset($postData['challan_amount']) ? $postData['challan_amount'] : '0',
                'p_instrument_no' => $postData['instrument_no'],
                'p_instrument_date' => HelperClass::dateFormatForDB($postData['instrument_date']),
                'p_currency_code' => $postData['currency'],
                'p_exchange_rate' => $postData['exc_rate'],
                'p_receipt_amount_ccy' => isset($postData['receipt_amt_ccy']) ? $postData['receipt_amt_ccy'] : '0',
                'p_receipt_amount_lcy' => isset($postData['receipt_amt_lcy']) ? $postData['receipt_amt_lcy'] : '0',
                'p_misc_receipt_ccy' => isset($postData['misc_amt_ccy']) ? $postData['misc_amt_ccy'] : '0',
                'p_misc_receipt_lcy' => isset($postData['misc_amt_lcy']) ? $postData['misc_amt_lcy'] : '0',
                'p_user_id' => auth()->id(),
                'p_system_generated_yn' => YesNoFlag::NO,
                "o_receipt_id" => [
                    'value' => &$receipt_id,
                    'type' => \PDO::PARAM_INT,
                    'length' => 255
                ],
                'o_trans_batch_id' => &$trans_batch_id,
                'o_document_no' => &$o_document_no,
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            //dd($params);

            DB::executeProcedure('cpaacc.fas_ar_trans.trans_ar_receipt_entry', $params);

            $oReceiptId = isset($params['o_receipt_id']['value']) ? $params['o_receipt_id']['value'] : '';

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
            }
            $master_params = $params;
            if ((count($files) > 0) && $oReceiptId) {
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
                        'p_receipt_id' => $oReceiptId,
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
                    DB::executeProcedure('cpaacc.fas_ar_trans.trans_ar_receipt_docs', $payFileParams);

                    $params = $payFileParams; // Last one should be assigned and return back!

                    if ($payFileParams['o_status_code'] != 1) {
                        DB::rollBack();
                        return response()->json(["response_code" => $pay_file_status_code, "response_msg" => $pay_file_status_message]);
                    }
                }
                //die();
            }

            //insert into Invoice Reference
            if ((count($invoiceReferences) > 0) && $oReceiptId) {
                foreach ($invoiceReferences as $invoiceReference) {
                    $invRefParams = [];
                    $inv_ref_status_code = sprintf("%4000s", "");
                    $inv_ref_status_message = sprintf('%4000s', '');
                    $invRefParams = [
                        'p_receipt_id' => $oReceiptId,
                        'p_invoice_id' => $invoiceReference['inv_ref_check'],
                        'p_receipt_amt' => $invoiceReference['receipt_amt'], // Add this part: Pavel-25-04-22
                        'p_user_id' => Auth()->ID(),
                        'o_status_code' => &$inv_ref_status_code,
                        'o_status_message' => &$inv_ref_status_message
                    ];

                    DB::executeProcedure('cpaacc.fas_ar_trans.trans_ar_receipt_invoices', $invRefParams);

                    $params = $invRefParams; // Last one should be assigned and return back!

                    if ($invRefParams['o_status_code'] != 1) {
                        DB::rollBack();
                        return response()->json(["response_code" => $inv_ref_status_code, "response_msg" => $inv_ref_status_message]);
                    }
                    //echo ($oPaymentId.'=='.$invoiceReference['inv_ref_check'].'=='.'<br>');
                }
                //die();
            }

            if ($oReceiptId) {

                $validate_ar_receipt_status_code = sprintf("%4000s", "");
                $validate_ar_receipt_status_message = sprintf("%4000s", "");

                $validateArReceiptParams = [
                    'p_receipt_id' => $oReceiptId,
                    'o_status_code' => &$validate_ar_receipt_status_code,
                    'o_status_message' => &$validate_ar_receipt_status_message,
                ];

                DB::executeProcedure('CPAACC.fas_ar_trans.validate_ar_receipt_entry', $validateArReceiptParams);

                if ($validateArReceiptParams['o_status_code'] != 1) {
                    DB::rollBack();
                    return response()->json(["response_code" => $validate_ar_receipt_status_code, "response_msg" => $validate_ar_receipt_status_message]);
                }
            }

            if ($oReceiptId) {
                $wk_mapping_status_code = sprintf("%4000s", "");
                $wk_mapping_status_message = sprintf("%4000s", "");

                $wkMappingParams = [
                    'P_WORKFLOW_MASTER_ID' => WorkFlowMaster::AR_INVOICE_BILL_RECEIPT_APPROVAL,
                    'P_REFERENCE_TABLE' => WkReferenceTable::FAS_AR_RECEIPT,
                    'P_REFERANCE_KEY' => WkReferenceColumn::AR_RECEIPT_ID,
                    'P_REFERANCE_ID' => $oReceiptId,
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
            //dd($e);
            DB::rollBack();
            //return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
            return response()->json(["response_code" => '99', 'response_msg' => $e->getMessage()]);
        }
        DB::commit();
        //return $params;
        //return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
        return response()->json(["response_code" => $status_code, "response_msg" => $status_message, "o_batch" => $trans_batch_id, "o_document_no" => $o_document_no, "period" =>$master_params['p_trans_period_id'] ]);

    }

    public function updateInvoiceEntry(Request $request)
    {
        $status_code = sprintf("%4000d","");
        $status_message = sprintf("%4000s","");

        $params = [
            'p_receipt_id' => $request->post('receiptId'),
            'p_trans_period_id' => $request->post('period'),
            'p_trans_date' => HelperClass::dateFormatForDB($request->post('postingDate')),
            'p_document_date' => HelperClass::dateFormatForDB($request->post('documentDate')),
            'p_document_no' => $request->post('documentNumber'),
            'p_document_ref' => $request->post('documentRef'),
            'p_department_id' => $request->post('department'),
            'p_bill_reg_id' => $request->post('billRegister'),
            'p_bill_sec_id' => $request->post('billSection'),
            'p_narration' => $request->post('documentNarration'),
            'p_instrument_no' => $request->post('instrumentNo'),
            'p_instrument_date' => HelperClass::dateFormatForDB($request->post('instrumentDate')),
            'p_user_id' => Auth()->id(),
            'o_status_code' => &$status_code,
            'o_status_message' => &$status_message
        ];

        DB::beginTransaction();
        try {
            DB::executeProcedure("CPAACC.fas_ar_trans.trans_ar_receipt_ref_update",$params);
            DB::commit();
        }catch (\Exception $e){
            return ['response_code'=>99, 'response_message'=>$e->getMessage()];
            DB::rollBack();
        }
        return ['response_code'=>$status_code, 'response_message'=>$status_message];
    }

}
