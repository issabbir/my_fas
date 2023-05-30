<?php
/**
 *Created by PhpStorm
 *Created at ২৩/১১/২১ ১১:০৯ AM
 */

namespace App\Http\Controllers\BudgetMonitoring;


use App\Contracts\Gl\GlContract;
use App\Contracts\LookupContract;
use App\Entities\BudgetManagement\FasBudgetMgtDocs;
use App\Entities\BudgetMonitoring\FasBudgetBookingDocs;
use App\Entities\Common\LBillSection;
use App\Entities\Common\LTenderType;
use App\Entities\Gl\GlCoa;
use App\Entities\Gl\GlCoaParams;
use App\Enums\BudgetManagement\SubmissionType;
use App\Enums\BudgetMonitoring\BmnFunctionType;
use App\Enums\BudgetMonitoring\TenderType;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\BudgetManagement\BudgetMgtLookupManager;
use App\Managers\BudgetMonitoring\BudgetMonitoringLookupManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPUnit\TextUI\Help;

class ConcurrenceTransactionController extends Controller
{
    private $lookupManager;
    private $monitoringManager;

    public function __construct(LookupContract $lookupManager)
    {
        $this->monitoringManager = new BudgetMonitoringLookupManager();
        $this->lookupManager = $lookupManager;
    }

    public function index($filter = null)
    {
        $filterData = isset($filter) ? explode('#', Crypt::decryptString($filter)) : $filter;

        $data['financialYear'] = $this->monitoringManager->getCurrentFinancialYear();
        $data['billSecs'] = $this->monitoringManager->getBillSections(BmnFunctionType::BUDGET_BOOKING);
//        dd( $data['billSecs'] );
        $data['lTenderType'] = $this->monitoringManager->getTenderTypes();
        $vendorType = $this->monitoringManager->getVendorTypes();
        $vendorCategory = $this->monitoringManager->getVendorCategory();
        $department = $this->lookupManager->getDeptCostCenter();
        $isRequired = [
            'document_required' => (DB::selectOne("select fas_policy.get_live_deploy_policy_flag from dual")->get_live_deploy_policy_flag == 1 ? 'readonly' : 'required' )
        ];
        return view('budget-monitoring.concurrence-transaction.index', compact('filterData','isRequired','data', 'department', 'vendorType', 'vendorCategory'));
    }

    public function store(Request $request, $id = null)
    {
        DB::beginTransaction();
        try {
            $budget_booking_id = '';
            //$actionType = isset($id) ? ProActionType::UPDATE : ProActionType::INSERT;
            $status_code = sprintf("%4000d", "");
            $status_message = sprintf("%4000s", "");
            $o_document_no = sprintf("%4000s", "");

            $params = [
                'p_fiscal_year_id' => $request->post('fiscal_year'),
                'p_cost_center_dept_id' => $request->post('department'),
                'p_budget_head_id' => $request->post('budget_head_id'),
                'p_bill_sec_id' => $request->post('bill_section'),
                'p_bill_reg_id' => $request->post('bill_register'),
                'p_trans_period_id' => $request->post('transaction_period'),
                'p_trans_date' => HelperClass::dateFormatForDB($request->post('transaction_date')),
                'p_document_date' => HelperClass::dateFormatForDB($request->post('document_date')),
                'p_document_no' => $request->post('document_no'),
                'p_file_no' => $request->post('fill_no'),
                'p_page_no' => $request->post('page_no'),
                'p_memo_no' => $request->post('memo_no'),
                'p_memo_date' => HelperClass::dateFormatForDB($request->post('memo_date')),
                'p_estimate_amount' => $request->post('est_amount'),
                'p_estimate_date' => HelperClass::dateFormatForDB($request->post('est_date')),
                'p_remarks' => $request->post('remarks'),
                'p_vendor_id' => $request->post('vendor_id'),
                'p_contract_id' => $request->post('contract_id'),
                'p_contract_no' => $request->post('contract_no'),
                'p_contract_date' => HelperClass::dateFormatForDB($request->post('contract_date')),
                'p_contract_party_name' => $request->post('party_name'),
                'p_contract_subject' => $request->post('subject'),
                'p_contract_value' => $request->post('contract_value'),
                'p_tender_proposal_no' => $request->post('tender_proposal_no'),
                'p_tender_proposal_ref' => $request->post('tender_proposal_ref'),
                'p_tender_proposal_date' => HelperClass::dateFormatForDB($request->post('tender_proposal_date')),
                'p_tender_type_id' => $request->post('tender_proposal_type'),
                'p_budget_booking_amount' => $request->post('booking_amount'),
                'p_user_id' => auth()->id(),
                'o_budget_booking_id' => [
                    "value" => &$budget_booking_id,
                    "type" => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'o_document_no' => &$o_document_no,
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message
            ];

            DB::executeProcedure('CPAACC.fas_budget.fas_budget_booking_make', $params);

            if ($params['o_status_code'] != "1") {
                DB::rollBack();
                return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
            } else {
                if (!is_null($request->file('attachment')) || !is_null($request->post('attachment'))) {
                    $files = $request->file('attachment');
                    $attachment = $request->post('attachment'); //actionType, docFileId, description
                    foreach ($attachment as $index => $file) {

                        if ((($file["actionType"] == 'I') && isset($files[$index]['file'])) || ($file["actionType"] == 'D')) {
                            $byteCode = ($file["actionType"] == 'I') ? base64_encode(file_get_contents($files[$index]['file']->getRealPath())) : '';
                            $fileExt = ($file["actionType"] == 'I') ? $files[$index]['file']->extension() : '';
                            $fileName = ($file["actionType"] == 'I') ? $files[$index]['file']->getClientOriginalName() : '';

                            $fileId = $file['docFileId'];
                            $file_status_code3 = sprintf("%4000d", "");
                            $file_status_message3 = sprintf("%4000s", "");

                            $file_params3 = [
                                'p_action_type' => $file['actionType'],
                                'p_budget_booking_id' => $budget_booking_id,
                                'doc_file_id' => $fileId,
                                'p_doc_file_name' => $fileName,
                                'p_doc_file_name_bng' => '',
                                'p_doc_file_desc' => $file["description"],
                                'p_doc_file_type' => $fileExt,
                                'p_doc_file_content' => [
                                    "value" => $byteCode,
                                    "type" => SQLT_CLOB
                                ],
                                'p_user_id' => auth()->id(),
                                'o_status_code' => &$file_status_code3,
                                'o_status_message' => &$file_status_message3
                            ];

                            DB::executeProcedure('CPAACC.fas_budget.fas_budget_booking_docs_attach', $file_params3);
                            if ($file_status_code3 != "1") {
                                DB::rollBack();
                                return response()->json(["response_code" => $file_status_code3, "response_msg" => $file_status_message3]);
                            }
                        }
                    }
                }
            }

            $wk_mapping_status_code = sprintf("%4000d", "");
            $wk_mapping_status_message = sprintf("%4000s", "");

            $wkMappingParams = [
                'P_WORKFLOW_MASTER_ID' => WorkFlowMaster::BUDGET_MON_BUDGET_CONCURRENCE_TRANSACTION_APPROVAL,
                'P_REFERENCE_TABLE' => WkReferenceTable::FAS_BUDGET_BOOKING_TRANS,
                'P_REFERANCE_KEY' => WkReferenceColumn::BUDGET_BOOKING_ID,
                'P_REFERANCE_ID' => $budget_booking_id,
                'P_TRANS_PERIOD_ID' => $request->post('transaction_period'),
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$wk_mapping_status_code,
                'o_status_message' => &$wk_mapping_status_message
            ];

            DB::executeProcedure('CPAACC.WORKFLOW_MAPPING_ENTRY', $wkMappingParams);

            if ($wkMappingParams['o_status_code'] != "1") {
                DB::rollBack();
                return response()->json(["response_code" => $wk_mapping_status_code, "response_msg" => $wk_mapping_status_message]);
            }
            //DB::rollBack();
            //dd("okay");
            DB::commit();
            return response()->json(["response_code" => $wk_mapping_status_code, "response_msg" => $status_message,"o_document_no" => $o_document_no, "booking_id" => $budget_booking_id]);

        } catch (\Exception $e) {
            DB::rollBack();
            //dd($e);
            return response()->json(["response_code" => "99", 'response_msg' => $e->getMessage()]);
        }
    }

    public function edit($booking_id, $mode,$filter=null)
    {
        $editType = Crypt::decryptString($mode);
        /**For transaction list transaction type always M **/
        $data['insertedData'] = DB::selectOne("select cpaacc.fas_budget.get_budget_booking_trans_view (:p_budget_booking_id, :p_transaction_type) from dual", ['p_budget_booking_id' => $booking_id, 'p_transaction_type' => 'M']);
        if (isset($data['insertedData'])) {
            $data['insertedData']->attachments = FasBudgetBookingDocs::where('budget_booking_id', $booking_id)->get();
        }
        $data['financialYear'] = $this->monitoringManager->getCurrentFinancialYear();
        $data['billSecs'] = $this->monitoringManager->getBillSections(BmnFunctionType::BUDGET_BOOKING);
        $data['lTenderType'] = $this->monitoringManager->getTenderTypes();
        $vendorType = $this->monitoringManager->getVendorTypes();
        $vendorCategory = $this->monitoringManager->getVendorCategory();
        $department = $this->lookupManager->getDeptCostCenter();

        if ($editType == "M") {
            return view('budget-monitoring.concurrence-transaction-listing.reference-edit.index', compact('filter','data', 'department', 'vendorType', 'vendorCategory', 'mode'));
        } elseif ($editType == "E") {
            return view('budget-monitoring.concurrence-transaction-listing.concurrence-edit.index', compact('filter','data', 'department', 'vendorType', 'vendorCategory', 'mode'));
        } elseif ($editType == "D") {
            return view('budget-monitoring.concurrence-transaction-listing.concurrence-delete.index', compact('filter','data', 'department', 'vendorType', 'vendorCategory', 'mode'));
        }
    }

    public function update(Request $request)
    {
        $editType = Crypt::decryptString($request->post('mode'));
        $o_status_message = sprintf("%4000s", "");
        $o_status_code = sprintf("%4000d", "");

        if ($editType == 'M') {

            $params = [
                'p_budget_booking_id' => $request->post('budget_booking_id')
                , 'p_trans_period_id' => $request->post('transaction_period')
                , 'p_trans_date' => HelperClass::dateFormatForDB($request->post('transaction_date'))
                , 'p_document_date' => HelperClass::dateFormatForDB($request->post('document_date'))
                , 'p_document_no' => $request->post('document_no')
                , 'p_bill_reg_id' => $request->post('bill_register')
                , 'p_bill_sec_id' => $request->post('bill_section')
                , 'p_user_id' => Auth()->id()
                , 'o_status_code' => &$o_status_code
                , 'o_status_message' => &$o_status_message
            ];

            DB::executeProcedure('CPAACC.fas_budget.fas_budget_booking_ref_update', $params);

        } elseif ($editType == 'E' || $editType == 'D') {
            $budget_book_tran_log_id = '';
            $params = [
                'p_transaction_type' => $editType
                , 'p_budget_booking_id' => $request->post('budget_booking_id')
                , 'p_fiscal_year_id' => $request->post('fiscal_year')
                , 'p_cost_center_dept_id' => $request->post('department')
                , 'p_budget_head_id' => $request->post('budget_head_id')
                , 'p_bill_sec_id' => $request->post('bill_section')
                , 'p_bill_reg_id' => $request->post('bill_register')
                , 'p_trans_period_id' => $request->post('transaction_period')
                , 'p_trans_date' => HelperClass::dateFormatForDB($request->post('transaction_date'))
                , 'p_document_date' => HelperClass::dateFormatForDB($request->post('document_date'))
                , 'p_document_no' => $request->post('document_no')
                , 'p_file_no' => $request->post('fill_no')
                , 'p_page_no' => $request->post('page_no')
                , 'p_memo_no' => $request->post('memo_no')
                , 'p_memo_date' => HelperClass::dateFormatForDB($request->post('memo_date'))
                , 'p_estimate_amount' => $request->post('est_amount')
                , 'p_estimate_date' => HelperClass::dateFormatForDB($request->post('est_date'))
                , 'p_remarks' => $request->post('remarks')
                , 'p_vendor_id' => $request->post('vendor_id')
                , 'p_contract_id' => $request->post('contract_id')
                , 'p_contract_no' => $request->post('contract_no')
                , 'p_contract_date' => HelperClass::dateFormatForDB($request->post('contract_date'))
                , 'p_contract_party_name' => $request->post('vendor_name')
                , 'p_contract_subject' => $request->post('subject')
                , 'p_contract_value' => $request->post('contract_value')
                , 'p_tender_proposal_no' => $request->post('tender_proposal_no')
                , 'p_tender_proposal_ref' => $request->post('tender_proposal_ref')
                , 'p_tender_proposal_date' => HelperClass::dateFormatForDB($request->post('tender_proposal_date'))
                , 'p_tender_type_id' => $request->post('tender_proposal_type')
                , 'p_budget_booking_amount' => $request->post('booking_amount')
                , 'p_budget_balance_amt' => $request->post('balance_amount')
                , 'p_user_id' => auth()->id()
                ,'o_budget_book_tran_log_id' => [
                    "value" => &$budget_book_tran_log_id,
                    "type" => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ]
                , 'o_status_code' => &$o_status_code
                , 'o_status_message' => &$o_status_message
            ];
            DB::executeProcedure('CPAACC.fas_budget.fas_budget_booking_log_make', $params);

            $wk_mapping_status_code = sprintf("%4000d", "");
            $wk_mapping_status_message = sprintf("%4000s", "");

            $wkMappingParams = [
                'P_WORKFLOW_MASTER_ID' => ($editType == 'E') ? WorkFlowMaster::BUDGET_MON_CONCURRENCE_TRANS_EDIT_AUTHORIZE : WorkFlowMaster::BUDGET_MON_CONCURRENCE_TRANS_DELETE_AUTHORIZE,
                'P_REFERENCE_TABLE' => WkReferenceTable::FAS_BUDGET_BOOKING_TRANS_LOG,
                'P_REFERANCE_KEY' => WkReferenceColumn::BUDGET_BOOK_TRAN_LOG_ID,
                'P_REFERANCE_ID' => $budget_book_tran_log_id,
                'P_TRANS_PERIOD_ID' => $request->post('transaction_period'),
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$wk_mapping_status_code,
                'o_status_message' => &$wk_mapping_status_message
            ];

            DB::executeProcedure('CPAACC.WORKFLOW_MAPPING_ENTRY', $wkMappingParams);

            if ($wkMappingParams['o_status_code'] != "1") {
                DB::rollBack();
            }
        }
        return response()->json(["response_code" => $o_status_code, "response_msg" => $o_status_message]);
    }
}
