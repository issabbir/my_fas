<?php

namespace App\Http\Controllers\BudgetManagement;

use App\Contracts\BudgetManagement\BudgetMgtLookupContract;
use App\Contracts\LookupContract;
use App\Entities\BudgetManagement\FasBudgetMgtDocs;
use App\Entities\Security\User;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\BudgetManagement\BudgetMgtLookupManager;
use App\Managers\BudgetManagement\BudgetMgtManager;
use App\Managers\LookupManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class bk_241221_AjaxController extends Controller
{

    protected $lookupManager;
    protected $attachment;
    private $budgetManager;

    /** @var BudgetMgtLookupManager */
    private $budgetMgtLookupManager;

    public function __construct(BudgetMgtLookupContract $budgetMgtLookupManager, LookupContract $lookupManager)
    {
        $this->budgetMgtLookupManager = $budgetMgtLookupManager;
        $this->attachment = new FasBudgetMgtDocs();
        $this->budgetManager = new BudgetMgtManager();
        $this->lookupManager = $lookupManager;
    }

    public function getDeptPeriod(Request $request)
    {
        $departments = $this->lookupManager->getDeptCostCenter();
        $periods = $this->lookupManager->findPostingPeriod($request->get("calendarId"));
        $preDpt = $request->get("pre_selected_dpt");
        $prePeriod = $request->get("pre_selected_period");

        /*$departmentHtml = "<option value=''>Select Department</option>";
        $periodHtml = "<option value=''>Select Period</option>";*/

        $departmentHtml = "<option value=''>Select Department</option>";
        $periodHtml = "";

        if (isset($departments)) {
            foreach ($departments as $dpt) {
                if (isset($preDpt) && ($preDpt == $dpt->cost_center_dept_id)) {
                    $departmentHtml .= "<option selected value='" . $dpt->cost_center_dept_id . "'>" . $dpt->cost_center_dept_name . "</option>";
                } else {
                    $departmentHtml .= "<option value='" . $dpt->cost_center_dept_id . "'>" . $dpt->cost_center_dept_name . "</option>";
                }
            }
        } else {
            $departmentHtml = "<option value=''></option>";
        }

        if (isset($periods)) {
            foreach ($periods as $period) {
                if (isset($prePeriod) && ($prePeriod == $period->posting_period_id)) {
                    $periodHtml .= "<option selected
                                        data-currentdate='" . HelperClass::dateConvert($period->current_posting_date) . "'
                                        data-mindate='" . HelperClass::dateConvert($period->posting_period_beg_date) . "'
                                        data-maxdate='" . HelperClass::dateConvert($period->posting_period_end_date) . "'
                                         value='" . $period->posting_period_id . "'>" . $period->posting_period_name . "</option>";

                } else {
                    $periodHtml .= "<option
                                        data-currentdate='" . HelperClass::dateConvert($period->current_posting_date) . "'
                                        data-mindate='" . HelperClass::dateConvert($period->posting_period_beg_date) . "'
                                        data-maxdate='" . HelperClass::dateConvert($period->posting_period_end_date) . "'
                                         value='" . $period->posting_period_id . "'>" . $period->posting_period_name . "</option>";
                }
            }
        } else {
            $periodHtml = "<option value=''></option>";
        }

        return response()->json(['department' => $departmentHtml, 'period' => $periodHtml]);
    }

    public function getInitialBudgetDetail(Request $request)
    {
        $budget_table = "";
        $status_code = sprintf("%4000d", null);
        $status_message = sprintf("%4000s", null);

        if ($request->get("load_for") == 'I') {
            $params = [
                'p_fiscal_year_id' => $request->get("fiscal_year"),
                'p_cost_center_dept_id' => $request->get("dept_id"),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message
            ];

            try {
                DB::executeProcedure('CPAACC.fas_budget.check_budget_initialized', $params);
            } catch (\Exception $e) {
                dd($e->getMessage());
            }

            if ($status_code == 1) {
                try {
                    $budget_table = $this->budgetHeadTableConstruct($request);
                } catch (\Throwable $e) {
                    $status_code = 99;
                    $status_message = $e->getMessage();
                }
            }
            return response()->json(['table' => $budget_table, 'status_code' => $status_code, 'status_message' => $status_message]);

        } else {
            try {
                $budget_table = $this->budgetHeadTableConstruct($request);
                $status_code = 1;
                $status_message = "No issue";
            } catch (\Throwable $e) {
                $status_code = 99;
                $status_message = $e->getMessage();
            }
            return response()->json(['table' => $budget_table, 'status_code' => $status_code, 'status_message' => $status_message]);
        }
    }

    public function reviewApprovalBudgetDetailsList(Request $request)
    {
        $postData = $request->post();
        $budgetMasterId = isset($postData['budget_master_id']) ? $postData['budget_master_id'] : '';
        $fiscalYear = $this->budgetMgtLookupManager->getACurrentFinancialYear();
        $budgets = [];

        if ($budgetMasterId) {
            $budgets = DB::select("select * from table (CPAACC.fas_budget.get_review_budget_details (:p_budget_master_id))", ['p_budget_master_id' => $budgetMasterId]);
        }

        $budget_table_head = $this->budgetManager->getBudgetTableHeader($fiscalYear->fiscal_year_id);

        //$html = view('budget-management.common_budget_detail_table',['budget_table_head'=>$budget_table_head])->with('budgets', $budgetDetailsList)->render();
        $html = view('budget-management.common_budget_detail_table')->with(compact('budgets', 'budget_table_head'))->render();

        $jsonArray = [
            'html' => $html
        ];

        return response()->json($jsonArray);
    }


    public function download($id)
    {
        $attachment = $this->attachment->where('doc_file_id', '=', $id)->first();
        $content = base64_decode($attachment->doc_file_content);

        return response()->make($content, 200, [
            'Content-Type' => $attachment->doc_file_type,
            'Content-Disposition' => 'attachment;filename="' . $attachment->doc_file_name . '"'
        ]);
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function budgetHeadTableConstruct(Request $request)
    {
        $budgets = DB::select("select * from table (CPAACC.fas_budget.get_initial_budget_details(:p_fiscal_year_id, :p_cost_center_dept_id))",
            [
                'p_fiscal_year_id' => $request->get("fiscal_year"),
                'p_cost_center_dept_id' => $request->get("dept_id")
            ]);

        $budget_table_head = $this->budgetManager->getBudgetTableHeader($request->get("ini_period_id"));
        return view("budget-management.common_budget_detail_table", compact('budgets', 'budget_table_head'))->render();
    }

}
