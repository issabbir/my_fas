<?php
/**
 *Created by PhpStorm
 *Created at ১৭/১১/২১ ৩:১০ PM
 */

namespace App\Http\Controllers\BudgetManagement;


use App\Contracts\Gl\GlContract;
use App\Contracts\LookupContract;
use App\Entities\BudgetManagement\FasBudgetMgtDocs;
use App\Entities\BudgetManagement\FasBudgetMgtMaster;
use App\Entities\Common\LCostCenterDept;
use App\Entities\Gl\GlCoa;
use App\Entities\Gl\GlCoaParams;
use App\Enums\BudgetManagement\BudgetWorkflowStatus;
use App\Enums\BudgetManagement\SubmissionType;
use App\Enums\ProActionType;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\BudgetManagement\BudgetMgtLookupManager;
use App\Managers\BudgetManagement\BudgetMgtManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PreparationController extends Controller
{
    private $lookupManager;
    private $glManager;
    private $glCoa;
    private $glCoaParam;
    private $budgetManager;
    private $attachment;

    public function __construct(LookupContract $lookupManager, GlContract $glManager)
    {
        $this->lookupManager = new BudgetMgtLookupManager();
        $this->budgetManager = new BudgetMgtManager();
        $this->glManager = $glManager;
        $this->glCoa = new GlCoa();
        $this->glCoaParam = new GlCoaParams();
        $this->attachment = new FasBudgetMgtDocs();
    }

    public function index()
    {
        $data['financialYear'] = $this->lookupManager->getCurrentFinancialYear();
        //$data['budgetDetailHead'] =
        /*$data['deptCostCenter'] = $this->lookupManager->getCurrentPostingPeriod();
        $data['postingPeriod'] = $this->lookupManager->getCurrentPostingPeriod();*/
        return view('budget-management.preparation.index', compact('data'));
    }

    public function store(Request $request)
    {
        $department = LCostCenterDept::find($request->post('department'));

        DB::beginTransaction();
        try {
            $budget_master_id = $request->post("budget_master");
            //$actionType = isset($id) ? ProActionType::UPDATE : ProActionType::INSERT;
            if ($request->post('submission_type') == SubmissionType::SAVE) {
                $status_code = sprintf("%4000d", "");
                $status_message = sprintf("%4000s", "");

                $params = [
                    'p_budget_master_id' => $budget_master_id,
                    'p_fiscal_calendar_id' => $request->post('fiscal_year'),
                    'p_cost_center_dept_id' => $request->post('department'),
                    'p_budget_estimation_type' => $request->post('estimation_type'),
                    'p_budget_init_period_id' => $request->post('initialization_period'),
/*                    'p_budget_init_date' => HelperClass::dateFormatForDB($request->post('initialization_date')),*/
                    'p_budget_init_remarks' => $request->post('remarks'),
                    'p_user_id' => auth()->id(),
                    'o_budget_master_id' => [
                        'value' => &$budget_master_id,
                        'type' => \PDO::PARAM_INPUT_OUTPUT,
                        'length' => 255
                    ],
                    'o_status_code' => &$status_code,
                    'o_status_message' => &$status_message
                ];

                //DB::executeProcedure('CPAACC.fas_budget.fas_budget_mgt_master_save', $params);
                DB::executeProcedure('CPAACC.fas_budget.fas_budget_est_master_save', $params);

                if ($params['o_status_code'] != "1") {
                    DB::rollBack();
                    return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
                } else {
                    $budgets = $request->post('budget');

                    foreach ($budgets as $key => $budget) {
                        $status_code2 = sprintf("%4000d", "");
                        $status_message2 = sprintf("%4000s", "");

                        $params2 = [
                            'p_budget_detail_id' => $budget['budget_detail_id'] ,
                            'p_budget_master_id' => $budget_master_id,
                            'p_budget_head_id' => $budget['budget_head_id'],
                            'p_col1_next_fy_proposed_amt' => isset($budget['est_next_fy']) ? HelperClass::removeCommaFromValue($budget['est_next_fy']) : 0,
                            'p_col2_curr_fy_revised_amt' =>isset( $budget['rev_curr_fy']) ? HelperClass::removeCommaFromValue($budget['rev_curr_fy']) : 0,
                            /*'p_col3_curr_fy_probable_amt' => isset($budget['probable_amt']) ? HelperClass::removeCommaFromValue($budget['probable_amt']) : 0 ,*/
                            'p_col4_curr_fy_concurred_amt' => isset($budget['concurred_amt']) ? HelperClass::removeCommaFromValue($budget['concurred_amt']) : 0,
                            'p_col5_curr_fy_estd_amt' => isset($budget['est_curr_fy']) ? HelperClass::removeCommaFromValue($budget['est_curr_fy']) : 0,
                            'p_col6_last_fy_prov_amt' => isset($budget['prov_last_fy']) ? HelperClass::removeCommaFromValue($budget['prov_last_fy']) : 0,
                            'p_user_id' => auth()->id(),
                            'o_status_code' => &$status_code2,
                            'o_status_message' => &$status_message2
                        ];

                        //DB::executeProcedure('CPAACC.fas_budget.fas_budget_mgt_detail_save', $params2);
                        DB::executeProcedure('CPAACC.fas_budget.fas_budget_est_detail_save', $params2);

                        if ($params2['o_status_code'] != "1") {
                            DB::rollBack();
                            return response()->json(["response_code" => $status_code2, "response_msg" => $status_message2 . "for budget ".$budget['budget_head_id']]);
                        }
                        /*echo ( 'v1=='.$budget['budget_detail_id'].
                            '<br>'.'v2=='.$budget_master_id.
                            '<br>'.'v3=='.$budget['budget_head_id'].
                            '<br>'.'v4=='.$budget['est_next_fy'].
                            '<br>'.'v5=='.$budget['probable_amt'].
                            '<br>'.'v6=='.$budget['concurred_amt'].
                            '<br>'.'v7=='.$budget['est_curr_fy'].
                            '<br>'.'v8=='.$budget['prov_last_fy'].
                            '<br>'.'key-'.$key.'-Status-'.$params2['o_status_code'].'=='.
                            $params2['o_status_message'].'<br>');*/
                    }
                    //die();

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
                                    'p_budget_master_id' => $budget_master_id,
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
                }
                DB::commit();
                //last_inserted_id is used to redirect in edit mode using js after form save
                return response()->json(["response_code" => "1", "response_msg" => "Saved Budget Data for ".ucwords(strtolower($department->cost_center_dept_name)).".", "last_inserted_id" => $budget_master_id]);

            } elseif (($request->post('submission_type') == SubmissionType::SUBMIT) && isset($budget_master_id)) {
                $wk_mapping_status_code = sprintf("%4000d", "");
                $wk_mapping_status_message = sprintf("%4000s", "");

                $wkMappingParams = [
                    'P_WORKFLOW_MASTER_ID' => WorkFlowMaster::BUDGET_MGT_BUDGET_INITIALIZATION_APPROVAL,
                    //'P_REFERENCE_TABLE' => WkReferenceTable::FAS_BUDGET_MGT_MASTER,
                    'P_REFERENCE_TABLE' => WkReferenceTable::FAS_BUDGET_EST_MASTER,
                    'P_REFERANCE_KEY' => WkReferenceColumn::BUDGET_MASTER_ID,
                    'P_REFERANCE_ID' => $budget_master_id,
                    'P_TRANS_PERIOD_ID' => $request->post('initialization_period'),
                    'P_INSERT_BY' => auth()->id(),
                    'o_status_code' => &$wk_mapping_status_code,
                    'o_status_message' => &$wk_mapping_status_message
                ];

                DB::executeProcedure('CPAACC.WORKFLOW_MAPPING_ENTRY', $wkMappingParams);

                if ($wkMappingParams['o_status_code'] != "1") {
                    DB::rollBack();
                    return response()->json(["response_code" => $wk_mapping_status_code, "response_msg" => $wk_mapping_status_message]);
                }

                DB::commit();
                return response()->json(["response_code" => $wk_mapping_status_code, "response_msg" => "Submitted Budget Data for ".ucwords(strtolower($department->cost_center_dept_name))."." ]);
            }else{
                return response()->json(["response_code" => "99", "response_msg" => "Submission Type Not Allowed."]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["response_code" => "99", 'response_msg' => $e->getMessage()]);
        }
    }

    public function datalist(Request $request)
    {
        $calendarId = $request->get('calendar_id');

        if (isset($calendarId)) {
            $budgets = $this->budgetManager->getInitialBudgetMasterList($calendarId);

        } else {
            $budgets = [];
        }
        return datatables()
            ->of($budgets)
            ->editColumn("budget_init_date", function ($data) {
                return HelperClass::dateConvert($data->budget_init_date);
            })
            ->editColumn("action", function ($data) {
                $html = "";

                /*
                 * 0003246: Budget Estimation Training issue (UI Modification Needed)
                 * if ($data->workflow_status_id != 0) {
                    $html = "<a style='text-decoration:underline' href='" . route('preparation.edit', [$data->budget_master_id, 'v']) . "' class='' data-target='' ><i class='bx bx-show'></i></a>";
                } else {
                    $html = "<a style='text-decoration:underline' href='" . route('preparation.edit', [$data->budget_master_id]) . "' class='text-right-align' data-target='' ><i class='bx bx-edit-alt'></i></a>";
                }*/

                if ($data->workflow_status_id == BudgetWorkflowStatus::BUDGET_INITIALIZE || $data->workflow_status_id == BudgetWorkflowStatus::BUDGET_FINALIZE ) {
                    $html = "<a style='text-decoration:underline' href='" . route('preparation.edit', [$data->budget_master_id]) . "' class='text-right-align' data-target='' ><i class='bx bx-edit-alt'></i></a>||<a style='text-decoration:underline' href='" . route('preparation.edit', [$data->budget_master_id, 'v']) . "' class='' data-target='' ><i class='bx bx-show'></i></a>";
                } else {
                    $html = "<a style='text-decoration:underline' href='" . route('preparation.edit', [$data->budget_master_id, 'v']) . "' class='' data-target='' ><i class='bx bx-show'></i></a>";
                }

                return $html;
            })
            ->rawColumns(["action"])
            ->make(true);
    }

    public function edit($id, $mode = null)
    {
        $data['financialYear'] = $this->lookupManager->getCurrentFinancialYear();
        $data['insertedData'] = FasBudgetMgtMaster::with("budgetDetail", "attachments")->where("budget_master_id", "=", $id)->first();
        $data['budget_table_head'] = $this->budgetManager->getBudgetTableHeader($data['insertedData']->fiscal_year_id);

        return view('budget-management.preparation.index', compact('data'));
    }

    public function update(Request $request)
    {
        dd($request);
    }

    public function view()
    {
        dd("Ok");
    }
}
