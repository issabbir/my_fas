<?php

namespace App\Http\Controllers\BudgetManagement;

use App\Contracts\BudgetManagement\BudgetMgtContract;
use App\Contracts\BudgetManagement\BudgetMgtLookupContract;
use App\Contracts\LookupContract;
use App\Entities\BudgetManagement\FasBudgetMgtDocs;
use App\Enums\ApprovalStatus;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Enums\WorkFlowRoleKey;
use App\Enums\YesNoFlag;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\BudgetManagement\BudgetMgtLookupManager;
use App\Managers\BudgetManagement\BudgetMgtManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use App\Traits\Security\HasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewApprovalController extends Controller
{
    use HasPermission;

    /** @var LookupManager */
    private $lookupManager;

    /** @var BudgetMgtLookupManager */
    private $budgetMgtLookupManager;

    /** @var BudgetMgtManager */
    private $budgetMgtManager;

    public function __construct(LookupContract $lookupManager, BudgetMgtLookupContract $budgetMgtLookupManager, BudgetMgtContract $budgetMgtManager)
    {
        $this->lookupManager = $lookupManager;
        $this->budgetMgtLookupManager = $budgetMgtLookupManager;
        $this->budgetMgtManager = $budgetMgtManager;
    }

    public function index()
    {
        return view('budget-management.review-approval.index');
    }

    public function dataTableList()
    {
        $queryResult = [];
        $userId = auth()->id();
        $fiscalYear = $this->budgetMgtLookupManager->getACurrentFinancialYear();
        $workflowWiseDpt = $this->budgetMgtManager->findWorkflowWiseDpt(WorkFlowMaster::BUDGET_MGT_BUDGET_INITIALIZATION_APPROVAL, WorkFlowRoleKey::BUDGET_MGT_DEPARTMENT_REVIEW, $userId);

        /** All Parameter Filter **/
        $params = [
            'p_fiscal_year_id' => isset($fiscalYear->fiscal_year_id) ? $fiscalYear->fiscal_year_id : null,
            'p_department_id' => isset($workflowWiseDpt->dpt_department_id) ? $workflowWiseDpt->dpt_department_id : null,
            'p_user_id' => $userId, //'2106300925--11',
        ];

        /** Execute Oracle Function With Params **/
        $sql = "select cpaacc.fas_budget.get_review_budget_master_list (:p_fiscal_year_id,:p_department_id,:p_user_id) from dual";
        $queryResult = DB::select($sql, $params);

        return datatables()->of($queryResult)
            ->editColumn('budget_init_date', function ($query) {
                return HelperClass::dateConvert($query->budget_init_date);
            })
            ->editColumn('status', function ($query) {
                if ($query->workflow_status_id == ApprovalStatus::WK_INITIALIZED) {
                    return '<span class="badge badge-danger badge-pill">' . $query->workflow_status_name . '</span>';
                } else if ($query->workflow_status_id == ApprovalStatus::WK_DEPARTMENT_REVIEWED) {
                    return '<span class="badge badge-dark badge-pill">' . $query->workflow_status_name . '</span>';
                } else if ($query->workflow_status_id == ApprovalStatus::WK_FINANCE_REVIEWED) {
                    return '<span class="badge badge-primary badge-pill">' . $query->workflow_status_name . '</span>';
                } else if ($query->workflow_status_id == ApprovalStatus::WK_BOARD_APPROVED) {
                    return '<span class="badge badge-warning badge-pill">' . $query->workflow_status_name . '</span>';
                } else {
                    return '<span class="badge badge-success badge-pill">' . $query->workflow_status_name . '</span>';
                }
            })
            ->addColumn('action', function ($query) {
                $statusIcon = isset($query->workflow_reference_status) && ($query->workflow_reference_status == ApprovalStatus::APPROVED) ? 'bx-show' : 'bx-edit';
                return '<a href="' . route('review-approval.approval-view', [$query->budget_master_id, 'wk_map_id' => $query->workflow_mapping_id, 'wk_ref_status' => $query->workflow_reference_status]) . '"><i class="bx ' . $statusIcon . ' cursor-pointer"></i></a>';
            })
            ->rawColumns(['budget_init_date', 'status', 'action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function approvalView(Request $request, $id)
    {
        $wkMapId = $request->get('wk_map_id');
        $wkRefStatus = $request->get('wk_ref_status');

        //$budgetMasterDocsList = FasBudgetMgtDocs::where('budget_master_id', $id)->get();
        if ($wkRefStatus == 'A') {
            $data['insertedData'] = (object)array('attachments' => FasBudgetMgtDocs::where('budget_master_id', $id)->get(), 'hide_delete'=>true);
        }else{
            $data['insertedData'] = (object)array('attachments' => FasBudgetMgtDocs::where('budget_master_id', $id)->get());
        }

        $budgetMaster = DB::selectOne("select cpaacc.fas_budget.get_review_budget_master_view (:p_budget_master_id) from dual", ['p_budget_master_id' => $id]);

        return view('budget-management.review-approval.approval_view', [
            'budgetMaster' => $budgetMaster,
            /*     'budgetMasterDocsList' => $budgetMasterDocsList,*/
            'wkMapId' => $wkMapId,
            'wkRefStatus' => $wkRefStatus,
            'data' =>$data

        ]);
    }

    public function approvalStore(Request $request)
    {
        $lastParams = [];
        $custom_message = '';
        $postData = $request->post();
        $deptName = $postData['department'];
        $budgetDetails = isset($postData['budget']) ? $postData['budget'] : [];

        ///dd($postData);

        try {
            DB::beginTransaction();

            if (count($budgetDetails) > 0) {
                foreach ($budgetDetails as $budgetDetail) {
                    $params = [];
                    //echo $experience['budget_head_id'].'==='.$experience['budget_detail_id'].'==='.$experience['est_next_fy'].'<br>';
                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf('%4000s', '');
                    $params = [
                        'p_budget_detail_id' => $budgetDetail['budget_detail_id'],
                        'p_budget_master_id' => $postData['budget_master_id'],
                        'p_budget_head_id' => $budgetDetail['budget_head_id'],
                        'p_col1_next_fy_proposed_amt' => isset($budgetDetail['est_next_fy']) ? HelperClass::removeCommaFromValue($budgetDetail['est_next_fy']) : 0,
                        'p_col2_curr_fy_revised_amt' => isset ($budgetDetail['rev_curr_fy']) ? HelperClass::removeCommaFromValue($budgetDetail['rev_curr_fy']) : 0,
                        /*'p_col3_curr_fy_probable_amt' => isset($budgetDetail['probable_amt']) ? $budgetDetail['probable_amt'] : 0,*/
                        'p_col4_curr_fy_concurred_amt' => isset($budgetDetail['concurred_amt']) ? HelperClass::removeCommaFromValue($budgetDetail['concurred_amt']) : 0 ,
                        'p_col5_curr_fy_estd_amt' => isset ($budgetDetail['est_curr_fy']) ? HelperClass::removeCommaFromValue($budgetDetail['est_curr_fy']) : 0,
                        'p_col6_last_fy_prov_amt' => isset($budgetDetail['prov_last_fy']) ? HelperClass::removeCommaFromValue($budgetDetail['prov_last_fy']) : 0,
                        'p_user_id' => Auth()->ID(),
                        'o_status_code' => &$status_code,
                        'o_status_message' => &$status_message
                    ];

                    //DB::executeProcedure('CPAACC.fas_budget.fas_budget_mgt_detail_save', $params);
                    DB::executeProcedure('CPAACC.fas_budget.fas_budget_est_detail_save', $params);

                    $lastParams = $params; // Last one should be assigned and return back!

                    if ($params['o_status_code'] != 1) {
                        DB::rollBack();
                        //return $params;
                        return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
                    }
                }

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
                                'p_budget_master_id' => $postData['budget_master_id'],
                                'p_doc_file_id' => $fileId,
                                'p_doc_file_name' => $fileName,
                                'p_doc_file_name_bng' => "",
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

                            //DB::executeProcedure('CPAACC.fas_budget.fas_budget_mgt_docs_attach', $file_params3);
                            DB::executeProcedure('CPAACC.fas_budget.fas_budget_est_docs_attach', $file_params3);
                            if ($file_status_code3 != "1") {
                                DB::rollBack();
                                return response()->json(["response_code" => $file_status_code3, "response_msg" => $file_status_message3]);
                            }
                        }
                    }
                }

                if ($postData['approve_save_value'] == ApprovalStatus::APPROVED) {
                    $wk_approval_status_code = sprintf("%4000s", "");
                    $wk_approval_status_message = sprintf("%4000s", "");

                    $wkApprovalParams = [
                        'i_workflow_mapping_id' => $postData['wk_map_id'],
                        'i_workflow_master_id' => WorkFlowMaster::BUDGET_MGT_BUDGET_INITIALIZATION_APPROVAL,
                        //'i_reference_table' => WkReferenceTable::FAS_BUDGET_MGT_MASTER,
                        'i_reference_table' => WkReferenceTable::FAS_BUDGET_EST_MASTER,
                        'i_reference_key' => WkReferenceColumn::BUDGET_MASTER_ID,
                        'i_reference_status' => ApprovalStatus::APPROVED,
                        'i_reference_comment' => null,
                        'i_user_id' => auth()->id(),
                        'o_status_code' => &$wk_approval_status_code,
                        'o_status_message' => &$wk_approval_status_message,
                    ];

                    DB::executeProcedure('CPAACC.WORKFLOW_APPROVAL_ENTRY', $wkApprovalParams);

                    if ($wkApprovalParams['o_status_code'] != 1) {
                        DB::rollBack();
                        return response()->json(["response_code" => $wk_approval_status_code, "response_msg" => $wk_approval_status_message]);
                    }
                }

                if ($postData['approve_save_value'] == ApprovalStatus::APPROVED) {
                    $custom_message = 'Approved Budget Data for ' . $deptName . '.';
                } else {
                    $custom_message = 'Saved Budget Data for ' . $deptName . '.';
                }
            }

        } catch (\Exception $e) {
            DB::rollBack();
            //return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
            return response()->json(["response_code" => '99', 'response_msg' => $e->getMessage()]);
        }
        DB::commit();
        //return $params;
        //return response()->json(["response_code" => $lastParams['o_status_code'], "response_msg" => $lastParams['o_status_message'] ]);
        return response()->json(["response_code" => $lastParams['o_status_code'], "response_msg" => $custom_message]);
    }

}
