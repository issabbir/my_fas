<?php

namespace App\Http\Controllers\BudgetManagement;

use App\Contracts\BudgetManagement\BudgetMgtLookupContract;
use App\Contracts\LookupContract;
use App\Entities\BudgetManagement\FasBudgetHead;
use App\Entities\BudgetManagement\FasBudgetMgtDocs;
use App\Entities\Gl\GlCoa;
use App\Entities\Security\User;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\BudgetManagement\BudgetMgtLookupManager;
use App\Managers\BudgetManagement\BudgetMgtManager;
use App\Managers\LookupManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class AjaxController extends Controller
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

        $departmentHtml = "<option value=''>&lt;Select&gt;</option>";
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
                'p_budget_estimation_type' => $request->get('estimation_type'),
	            'p_budget_init_period_id' => $request->get('ini_period_id'),
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
        $intPeriodId = isset($postData['initialization_period_id']) ? $postData['initialization_period_id'] : '';
        $budgetMasterId = isset($postData['budget_master_id']) ? $postData['budget_master_id'] : '';
        $fiscalYear = $this->budgetMgtLookupManager->getACurrentFinancialYear();
        $budgets = [];

        if ($budgetMasterId) {
            $budgets = DB::select("select * from table (CPAACC.fas_budget.get_review_budget_details (:p_budget_master_id))", ['p_budget_master_id' => $budgetMasterId]);
        }

        //$budget_table_head = $this->budgetManager->getBudgetTableHeader($fiscalYear->fiscal_year_id);
        $budget_table_head = $this->budgetManager->getBudgetTableHeader($intPeriodId);

        //$budget_estimation_policy = DB::selectOne("select cpaacc.fas_policy.get_budget_estimation_policy from dual");

        $estimationType = $request->post('estimation_type');
        //$html = view('budget-management.common_budget_detail_table',['budget_table_head'=>$budget_table_head])->with('budgets', $budgetDetailsList)->render();
        $html = view('budget-management.common_budget_detail_table')->with(compact('budgets', 'budget_table_head','estimationType'))->render();

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
        $estimationType = $request->get('estimation_type');

        $budgets = DB::select("select * from table (CPAACC.fas_budget.get_initial_budget_details(:p_fiscal_year_id, :p_cost_center_dept_id,:p_budget_init_period_id))",
            [
                'p_fiscal_year_id' => $request->get("fiscal_year"),
                'p_cost_center_dept_id' => $request->get("dept_id"),
                'p_budget_init_period_id' => $request->get('ini_period_id')
            ]);

        $budget_table_head = $this->budgetManager->getBudgetTableHeader($request->get("ini_period_id"));

        //0003246: Budget Estimation Training issue (UI Modification Needed)
        //$budget_estimation_policy = DB::selectOne("select cpaacc.fas_policy.get_budget_estimation_policy from dual");
        return view("budget-management.common_budget_detail_table", compact('budgets', 'budget_table_head', 'estimationType'))->render();
    }

    public function budgetHeadTreeOnType(Request $request)
    {
        $queryResult = DB::select("select CPAACC.fas_budget.get_budget_parent_tree_chart (:p_budget_type_id) from dual", ['p_budget_type_id' => $request->post('type_id','')]);
        $html = view('budget-management.budget-account-setup.budget_tree',['budget_heads'=>$queryResult])->render();
        return response()->json($html);
    }

    public function budgetHeadDataList(Request $request)
    {
        $searchTerm = $request->post('budget_name_code');

        if (empty($searchTerm)){
            $queryResult = [];
        } else {
            $queryResult = FasBudgetHead::with(['budget_type'])
                ->where(function ($query) use ($searchTerm) {
                    $query->where(DB::raw('LOWER(budget_head_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'))
                        ->orWhere('budget_head_id', 'like', '' . trim($searchTerm) . '%');
                })
                ->orderBy('budget_head_id', 'asc')
                ->get();
        }

        return datatables()->of($queryResult)
            ->addColumn('action', function ($query) {
                return '<a href="' . route('budget-head.budget-head-view', [$query->budget_head_id]) . '"><i class="bx bx-show cursor-pointer"></i></a>|<a href="' . route('budget-head.budget-head-edit', [$query->budget_head_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
            })
            ->make(true);
    }

    public function budgetHeadsearchDataList(Request $request)
    {
        $searchTerm = $request->post('budget_name_code');

        if (empty($searchTerm)){
            $queryResult = [];
        } else {
            $queryResult = FasBudgetHead::with(['budget_type'])
                ->where(function ($query) use ($searchTerm) {
                    $query->where(DB::raw('LOWER(budget_head_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'))
                        ->orWhere('budget_head_id', 'like', '' . trim($searchTerm) . '%');
                })
                ->orderBy('budget_head_id', 'asc')
                ->get();
        }

        return datatables()->of($queryResult)
            ->addColumn('action', function ($query) {
                return '<a href="' . route('head-edit.headView', [$query->budget_head_id]) . '"><i class="bx bx-show cursor-pointer"></i></a>|<a href="' . route('head-edit.headEdit', [$query->budget_head_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
            })
            ->make(true);
    }

    public function coaAccTree(Request $request)
    {
        $queryResult = DB::select("select CPAACC.fas_gl_config.get_gl_tree_chart() from dual");
        $html = view('budget-management.budget-account-setup.coa_tree',['gl_chart_list'=>$queryResult])->render();
        return response()->json($html);
    }

    public function coaInfoDetails($accountId)
    {
        $queryResult = GlCoa::with(['acc_type'])
            ->where(function ($query) use ($accountId) {
                $query->where(DB::raw('LOWER(gl_acc_name)'), 'like', strtolower('%' . trim($accountId) . '%'))
                    ->orWhere('gl_acc_code', 'like', '' . trim($accountId) . '%')
                    ->orWhere('gl_acc_id', 'like', '' . trim($accountId) . '%');
            })
            ->orderBy('gl_acc_id', 'asc')
            ->first();
        return response()->json($queryResult);
    }

    public function getCategoriesForBudget($id, $default=null)
    {
        $categories = $this->lookupManager->getCategoriesOnBudgetType($id);
        $options = '<option value="">&lt;select&gt;</option>';
        foreach ($categories as $category){
            if($default == $category->budget_category_id){
                $options .= '<option value="'.$category->budget_category_id.'" selected>'.$category->budget_category_name.'</option>';
            }else{
                $options .= '<option value="'.$category->budget_category_id.'">'.$category->budget_category_name.'</option>';
            }
        }

        return response()->json(['options'=>$options]);
    }

    public function getSubCategoriesForCategory($id, $default=null)
    {
        $sub_categories = $this->lookupManager->getSubCategoriesOnCategory($id);
        $options = '<option value="">&lt;select&gt;</option>';
        foreach ($sub_categories as $category){
            if ($default == $category->budget_sub_category_id){
                $options .= '<option value="'.$category->budget_sub_category_id.'" selected>'.$category->budget_sub_category_name.'</option>';
            }else{
                $options .= '<option value="'.$category->budget_sub_category_id.'">'.$category->budget_sub_category_name.'</option>';
            }
        }

        return response()->json(['options'=>$options]);
    }

}
