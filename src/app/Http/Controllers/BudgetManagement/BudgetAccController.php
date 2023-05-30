<?php


namespace App\Http\Controllers\BudgetManagement;


use App\Contracts\LookupContract;
use App\Entities\BudgetManagement\FasBudgetHead;
use App\Enums\ProActionType;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Enums\YesNoFlag;
use App\Helpers\HelperClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BudgetAccController
{
    private $lookupManager;

    public function __construct(LookupContract $lookupManager)
    {
        $this->lookupManager = $lookupManager;
    }

    public function index()
    {
        $budget_list = DB::select("select CPAACC.fas_budget.get_budget_tree_chart() from dual");
        return view('budget-management.budget-account-setup.index', [
            'budget_list' => $budget_list,
            //'glTransDtl' => $glTransDtl,
        ]);
    }

    public function headSetup()
    {
        return view('budget-management.budget-account-setup.setup', [
            'budgetTypes' => $this->lookupManager->getBudgetTypes(),
            'date' => $this->lookupManager->findCurDate(),
            'dptCostCenterList' => $this->lookupManager->getDeptCostCenter(),
            'dptClusterList' => $this->lookupManager->getDeptClusters(),
        ]);
    }

    public function edit(Request $request, $id)
    {
        $headInfo = FasBudgetHead::with(['gl_coa', 'head_parent_info', 'budget_type', 'budget_category', 'budget_sub_category', 'department', 'department_cluster'])->where('budget_head_id', $id)->first();
        return view('budget-management.budget-account-setup.setup', [
            'budgetTypes' => $this->lookupManager->getBudgetTypes(),
            'date' => $this->lookupManager->findCurDate(),
            'headInfo' => $headInfo,
            'dptCostCenterList' => $this->lookupManager->getDeptCostCenter(),
            'dptClusterList' => $this->lookupManager->getDeptClusters(),
        ]);
    }

    public function view(Request $request, $id)
    {
        $headInfo = FasBudgetHead::with(['gl_coa', 'head_parent_info', 'budget_type', 'budget_category', 'budget_sub_category', 'department', 'department_cluster','budget_booking_dept'])->where('budget_head_id', $id)->first();
       //dd($headInfo);
        return view('budget-management.budget-account-setup.view', compact('headInfo'));
    }

    public function store(Request $request, $id=null)
    {

        DB::beginTransaction();
        try {
            $budget_id = isset($id) ? $id : '';
            $actionType = isset($id) ? ProActionType::UPDATE : ProActionType::INSERT;
            $status_code = sprintf("%4000d", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_action_type' => $actionType,
                'p_budget_head_id' => $budget_id,
                'p_budget_head_name' => $request->post('head_name'),
                'p_budget_type_id' => $request->post('budget_type'),
                'p_budget_category_id' => $request->post('category'),
                'p_budget_sub_category_id' => $request->post('sub_category'),
                'p_budget_parent_id' => $request->post('parent_head_id'),
                'p_postable_yn' => $request->post('head_posting'),
                'p_cost_center_dept_id' => $request->post('department_id'),
                'p_cost_center_cluster_id' => $request->post('cluster_id'),
                'p_opening_date' => HelperClass::dateFormatForDB($request->post('opening_date')),
                'p_inactive_yn' => ($request->post('inactive_yn') != null) ? 'Y' : 'N',
                'p_inactive_date' => ($request->post('inactive_date') != null) ? HelperClass::dateFormatForDB($request->post('inactive_date')) : '',
                'p_user_id' => Auth::id(),
                'p_budget_booking_req_yn' => $request->post('booking_check', 'N'),
                'p_budget_booking_dept_id' => $request->post('booking_dept'),
                'p_gl_acc_id' => $request->post('parent_acc_code'),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message
            ];

            DB::executeProcedure('CPAACC.fas_budget.create_update_budget_head', $params);

            if ($status_code != "1") {

                DB::rollBack();
                if (isset($id)) {
                    return $params;
                }
                return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
            } else {

                DB::commit();
                if (isset($id)) {
                    return $params;
                }
                return response()->json(["response_code" => "1", "response_msg" => $status_message, "o_batch" => $budget_id,]);
            }
        } catch (\Exception $e) {

            DB::rollBack();
            if (isset($id)) {
                $params['o_status_code'] = 99;
                $params['o_status_message'] = $e->getMessage();
                return $params;
            }
            return response()->json(["response_code" => '99', 'response_msg' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {

        $response = $this->store($request, $id);
        return response()->json(["response_code" => $response['o_status_code'], 'response_msg' => $response['o_status_message']]);
    }

    public function  headEditIndex(){

        $budget_list = DB::select("select CPAACC.fas_budget.get_budget_tree_chart() from dual");

        return view('budget-management.budget-head.index', [
            'budget_list' => $budget_list,

        ]);
    }

    public function headEdit(Request $request, $id)
    {

        $headInfo = FasBudgetHead::with(['gl_coa', 'head_parent_info', 'budget_type', 'budget_category', 'budget_sub_category', 'department', 'department_cluster'])->where('budget_head_id', $id)->first();
        return view('budget-management.budget-head.edit', [
            'budgetTypes' => $this->lookupManager->getBudgetTypes(),
            'date' => $this->lookupManager->findCurDate(),
            'headInfo' => $headInfo,
            'dptCostCenterList' => $this->lookupManager->getDeptCostCenter(),
            'dptClusterList' => $this->lookupManager->getDeptClusters(),
        ]);
    }

    public function headView(Request $request, $id)
    {
        $headInfo = FasBudgetHead::with(['gl_coa', 'head_parent_info', 'budget_type', 'budget_category', 'budget_sub_category', 'department', 'department_cluster','budget_booking_dept'])->where('budget_head_id', $id)->first();
        //dd($headInfo);
        return view('budget-management.budget-head.view', compact('headInfo'));
    }

    public function headUpdate(Request $request, $id)
    {

        $response = $this->store($request, $id);
        return response()->json(["response_code" => $response['o_status_code'], 'response_msg' => $response['o_status_message']]);
    }

}
