<?php


namespace App\Http\Controllers\Gl;

use App\Contracts\Gl\GlContract;
use App\Contracts\LookupContract;
use App\Enums\Ap\ApFunType;
use App\Enums\BudgetMonitoring\BmnFunctionType;
use App\Enums\Common\DebitCredit;
use App\Enums\Common\LGlInteFun;
use App\Enums\Common\LGlInteModules;
use App\Enums\Common\LTransAmtType;
use App\Enums\Gl\FunctionTypes;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Http\Controllers\Controller;
use App\Enums\ProActionType;
use App\Enums\YesNoFlag;
use App\Helpers\HelperClass;
use App\Managers\FlashMessageManager;
use App\Managers\Gl\GlManager;
use App\Managers\LookupManager;
use App\Traits\Security\HasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashTransferController extends Controller
{
    use HasPermission;

    /** @var LookupManager */
    private $lookupManager;

    /** @var GlManager */
    private $glManager;

    public function __construct(LookupContract $lookupManager, GlContract $glManager)
    {
        $this->lookupManager = $lookupManager;
        $this->glManager = $glManager;
    }

    public function index()
    {
        $parentId = LGlInteFun::CASH_TRANS_VOUCHER;
        $moduleId = LGlInteModules::FIN_ACC_GENE_LEDGER;
        $user_id = auth()->id();

        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        $isRequired = [
            'document_required' => (DB::selectOne("select fas_policy.get_live_deploy_policy_flag from dual")->get_live_deploy_policy_flag == 1 ? 'readonly' : 'required' )
        ];
        return view('gl.cash-transfer.index', [
            'dptList' => $this->lookupManager->getDeptCostCenter(),
            'lBillSecList' => $this->lookupManager->getBillSections(FunctionTypes::BANK_TRANSFER),
//            'billRegs' => $this->lookupManager->getBillRegisterOnFunction(FunctionTypes::BANK_TRANSFER),

            //'postPeriodList' => $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id),
            'fiscalYear' => $fiscalYear,
            'cashTranFunTypeList' => $this->lookupManager->getIntegrationFunType($parentId, $moduleId),
            'lastGlTranMst' => $this->glManager->findLastGlTranMst($parentId, $user_id),
            'isRequired'=>$isRequired
        ]);
    }

    public function store(Request $request)
    {
        $response = $this->cash_transfer_api_ins($request);

        $message = $response['o_status_message'];

        if ($response['o_status_code'] != 1) {
//            session()->flash('m-class', 'alert-danger');
//            return redirect()->back()->with('message', $message)->withInput();
            return response()->json(["response_code" => $response['o_status_code'], "response_msg" => $message]);
        }

        /*session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('cash-transfer.index');*/
        return response()->json(["response_code" => "1", "response_msg" => $message, "o_batch" => $response['o_trans_batch_id']['value'], "o_document_no" => $response['o_document_no'], "period" => $response['p_trans_period_id']]);

    }

    private function cash_transfer_api_ins(Request $request)
    {
        $postData = $request->post();
        $posting_date = isset($postData['posting_date']) ? HelperClass::dateFormatForDB($postData['posting_date']) : '';
        $document_date = isset($postData['document_date']) ? HelperClass::dateFormatForDB($postData['document_date']) : '';
        $d_bank_acc_id = isset($postData['d_bank_acc_id']) ? ($postData['d_bank_acc_id']) : '';
        $c_bank_acc_id = isset($postData['c_bank_acc_id']) ? ($postData['c_bank_acc_id']) : '';
        $c_cheque_no = isset($postData['c_cheque_no']) ? ($postData['c_cheque_no']) : '';
        $c_cheque_date = isset($postData['c_cheque_date']) ? HelperClass::dateFormatForDB($postData['c_cheque_date']) : '';
        $c_without_cheque_yn = isset($postData['c_without_cheque']) && ($postData['c_without_cheque'] == YesNoFlag::YES) ? YesNoFlag::YES : YesNoFlag::NO;

        DB::beginTransaction();

        try {
            $trans_mst_id = null;
            $o_trans_batch_id = null;
            $o_document_no = sprintf("%4000s", "");
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_action_type' => ProActionType::INSERT,
                'p_trans_master_id' => [
                    'value' => &$trans_mst_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'p_module_id' => LGlInteModules::FIN_ACC_GENE_LEDGER,
                'p_function_id' => $postData['fun_type_id'],
                'p_department_id' => $postData['department'],
                'p_bill_sec_id' => $postData['bill_sec_id'],
                'p_bill_reg_id' => $postData['bill_reg_id'],
                'p_trans_period_id' => $postData['period'],
                'p_trans_date' => $posting_date,
                'p_document_date' => $document_date,
                'p_document_no' => $postData['document_number'],
                'p_document_ref' => $postData['document_reference'],
                'p_narration' => $postData['narration'],
                'p_user_id' => auth()->id(),
                'p_system_generated_yn' => 'N',
                /*0003216: FAS TRANSACTIONS: CHANGE OUTPUT PARAMETERS*/
                'o_trans_batch_id' => [
                    'value' => &$o_trans_batch_id,
                    'type' => \PDO::PARAM_INT
                ],
                'o_document_no' => &$o_document_no,
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,

            ];

            DB::executeProcedure('CPAACC.fas_gl_trans.trans_gl_master_make', $params);
            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }

            $o_trans_mst_id = $params['p_trans_master_id']['value'];
            if ($o_trans_mst_id && $d_bank_acc_id) {
                $debit_status_code = sprintf("%4000s", "");
                $debit_status_message = sprintf("%4000s", "");

                $dtlDebitParams = [
                    'p_action_type' => ProActionType::INSERT,
                    'p_trans_detail_id' => NULL,
                    'p_trans_master_id' => $o_trans_mst_id,
                    'p_gl_acc_id' => $d_bank_acc_id,
                    'p_dr_cr' => DebitCredit::DEBIT,
                    'p_currency_code' => $postData['d_currency'],
                    'p_exchange_rate' => $postData['d_exchange_rate'],
                    'p_amount_type_id' => LTransAmtType::GENERAL_OTHERS,
                    'p_amount_ccy' => $postData['d_amount_ccy'],
                    'p_amount_lcy' => $postData['d_amount_lcy'],
                    //'p_narration' => $postData['d_narration'],
                    'p_without_cheque_yn' => isset($request->c_cheque_no) ? 'N' : 'Y',
                    'p_cheque_no' => NULL,//isset($request->c_cheque_no) ? $request->c_cheque_no : NULL,
                    'p_cheque_date' => NULL,//isset($request->c_cheque_date) ? HelperClass::dateFormatForDB($request->c_cheque_date) : NULL,
                    'p_without_challan_yn' => NULL,
                    'p_challan_type_id' => NULL,
                    'p_challan_no' => NULL,
                    'p_challan_date' => NULL,
                    'p_gl_subsidery_id' => NULL,
                    'p_customer_id' => NULL,
                    'p_vendor_id' => NULL,
                    'p_emp_id' => NULL,
                    'p_budget_dept_id' =>  NULL,
                    'p_budget_head_id' => NULL,
                    'p_user_id' => auth()->id(),
                    'o_status_code' => &$debit_status_code,
                    'o_status_message' => &$debit_status_message,

                ];
                DB::executeProcedure('CPAACC.fas_gl_trans.trans_gl_detail_make', $dtlDebitParams);

                if ($dtlDebitParams['o_status_code'] != 1) {
                    DB::rollBack();
                    return $dtlDebitParams;
                }
            }

            if ($o_trans_mst_id && $c_bank_acc_id) {
                $credit_status_code = sprintf("%4000s", "");
                $credit_status_message = sprintf("%4000s", "");

                $dtlCreditParams = [
                    'p_action_type' => ProActionType::INSERT,
                    'p_trans_detail_id' => NULL,
                    'p_trans_master_id' => $o_trans_mst_id,
                    'p_gl_acc_id' => $c_bank_acc_id,
                    'p_dr_cr' => DebitCredit::CREDIT,
                    'p_currency_code' => $postData['c_currency'],
                    'p_exchange_rate' => $postData['c_exchange_rate'],
                    'p_amount_type_id' => LTransAmtType::GENERAL_OTHERS,
                    'p_amount_ccy' => $postData['c_amount_ccy'],
                    'p_amount_lcy' => $postData['c_amount_lcy'],
                    //'p_narration' => $postData['c_narration'],
                    'p_without_cheque_yn' => YesNoFlag::YES,//$c_without_cheque_yn, CPA don't want cheque area,
                    'p_cheque_no' => $c_cheque_no,
                    'p_cheque_date' => $c_cheque_date,
                    'p_without_challan_yn' => NULL,
                    'p_challan_type_id' => NULL,
                    'p_challan_no' => NULL,
                    'p_challan_date' => NULL,
                    'p_gl_subsidery_id' => NULL,
                    'p_customer_id' => NULL,
                    'p_vendor_id' => NULL,
                    'p_emp_id' => NULL,
                    'p_budget_dept_id' =>  NULL,
                    'p_budget_head_id' => NULL,
                    'p_user_id' => auth()->id(),
                    'o_status_code' => &$credit_status_code,
                    'o_status_message' => &$credit_status_message,

                ];

                DB::executeProcedure('CPAACC.fas_gl_trans.trans_gl_detail_make', $dtlCreditParams);

                if ($dtlCreditParams['o_status_code'] != 1) {
                    DB::rollBack();
                    return $dtlCreditParams;
                }
            }

            if ($o_trans_mst_id) {
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
                                'p_action_type' => 'I',
                                'p_trans_master_id' => $o_trans_mst_id,
                                'p_trans_doc_file_id' => [
                                    "value" => &$fileId,
                                    "type" => \PDO::PARAM_INPUT_OUTPUT,
                                    "length" => 256
                                ],
                                'p_trans_doc_file_name' => $fileName,
                                'p_trans_doc_file_name_bng' => "",
                                'p_trans_doc_file_desc' => $descriptions[$key]["description"],
                                'p_trans_doc_file_type' => $fileExt,
                                'p_trans_doc_file_content' => [
                                    "value" => $byteCode,
                                    "type" => SQLT_CLOB
                                ],
                                'p_user_id' => auth()->id(),
                                'o_status_code' => &${"file_status_code" . $key},
                                'o_status_message' => &${"file_status_message" . $key}
                            ];
                            DB::executeProcedure('CPAACC.fas_gl_trans.trans_gl_docs_attach', ${"file_params" . $key});

                            if (${"file_status_code" . $key} == "99") {
                                DB::rollBack();
                                return ${"file_params" . $key};
                            }
                        }
                    }
                }
            }

            if ($o_trans_mst_id) {
                $validate_trans_status_code = sprintf("%4000s", "");
                $validate_trans_status_message = sprintf("%4000s", "");

                $validateTransParams = [
                    'p_trans_master_id' => $o_trans_mst_id,
                    'o_status_code' => &$validate_trans_status_code,
                    'o_status_message' => &$validate_trans_status_message,
                ];

                DB::executeProcedure('CPAACC.fas_gl_trans.validate_transaction_batch', $validateTransParams);

                if ($validateTransParams['o_status_code'] != 1) {
                    DB::rollBack();
                    return $validateTransParams;
                }
            }


            /* echo '<pre>';
            print_r($dtlDebitParams);print_r($dtlCreditParams);print_r($params);//print_r($wkMappingParams);
            die();*/

            if ($o_trans_mst_id) {
                $wk_mapping_status_code = sprintf("%4000s", "");
                $wk_mapping_status_message = sprintf("%4000s", "");

                $wkMappingParams = [
                    'P_WORKFLOW_MASTER_ID' => WorkFlowMaster::GL_TRANSFER_VOUCHER_APPROVAL,
                    'P_REFERENCE_TABLE' => WkReferenceTable::FAS_GL_TRANS_MASTER,
                    'P_REFERANCE_KEY' => WkReferenceColumn::TRANS_MASTER_ID,
                    'P_REFERANCE_ID' => $o_trans_mst_id,
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


        } catch (\Exception $e) {

            DB::rollBack();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        DB::commit();
        return $params;
    }
}
