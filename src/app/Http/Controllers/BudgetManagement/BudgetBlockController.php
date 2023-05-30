<?php


namespace App\Http\Controllers\BudgetManagement;


use App\Contracts\LookupContract;
use App\Enums\BudgetMonitoring\BmnFunctionType;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\BudgetMonitoring\BudgetMonitoringLookupManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BudgetBlockController extends Controller
{
    private $lookupManager;
    private $monitoringManager;

    public function __construct(LookupContract $lookupManager)
    {
        $this->monitoringManager = new BudgetMonitoringLookupManager();
        $this->lookupManager = $lookupManager;
    }
    public function index()
    {
        $data['financialYear'] = $this->monitoringManager->getCurrentFinancialYear();

        $data['billSecs'] = $this->monitoringManager->getBillSections(BmnFunctionType::BUDGET_BOOKING);
        $data['lTenderType'] = $this->monitoringManager->getTenderTypes();
        $vendorType = $this->monitoringManager->getVendorTypes();
        $vendorCategory = $this->monitoringManager->getVendorCategory();
        $department = $this->lookupManager->getDeptCostCenter();

        return view('budget-management.block-amount.index', compact('data', 'department', 'vendorType', 'vendorCategory'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $status_code = sprintf("%4000d","");
        $status_msg = sprintf("%4000s","");
        $blocked_id = sprintf("%4000d","");
        $params = [
            'p_blocking_date' => HelperClass::dateFormatForDB($request->post('block_date')),
            'p_fiscal_year_id' => $request->post('fiscal_year'),
            'p_cost_center_dept_id' => $request->post('department'),
            'p_budget_head_id' => $request->post('budget_head_id'),
            'p_block_amount' => $request->post('new_blocked_amount'),
            'p_block_descrip' => $request->post('description'),
            'p_insert_by' => Auth::id(),
            //'p_insert_date' => ,
            //'p_update_by' => $request->post(''),
            //'p_update_date' => $request->post(''),
            'o_budget_blocking_id' => &$blocked_id,
            'o_status_code' => &$status_code,
            'o_status_message' => &$status_msg
        ];

        try {
            DB::executeProcedure('fas_budget.fas_budget_blocking_make',$params);
        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(['response_code'=>99,'response_msg'=>$e->getMessage()]);
        }
        if ($status_code != 1){
            DB::rollBack();
        }else{
            DB::commit();
        }
        return response()->json(['response_code'=>$status_code,'response_msg'=>$status_msg.' '.$blocked_id]);
    }

    public function blockHistory($blockId)
    {
        $histories = DB::select('select fas_budget.get_budget_unblock_history(:p_budget_blocking_id) from dual',['p_budget_blocking_id'=>$blockId]);
        $tbody = '';

        foreach ($histories as $history){
            $tbody .= '<tr><td>'.HelperClass::dateConvert($history->unblock_date).'</td><td class="text-right-align">'.HelperClass::getCommaSeparatedValue($history->unblock_amount).'</td><td>'.$history->unblock_remarks.'</td></tr>';
        }
        $info = DB::selectOne('select fas_budget.get_budget_unblock_info(:p_budget_blocking_id) from dual',['p_budget_blocking_id'=>$blockId]);

        if (isset($info)){
            $info->budget_blocking_date = HelperClass::dateConvert($info->budget_blocking_date);
        }
        return response()->json(['tbody'=>$tbody,'info'=>$info]);
    }

    public function dataList(Request $request)
    {
        $fiscal_year = $request->get('fiscal_year');
        $department = $request->get('department',null);
        $budget = $request->get('budget_head',null);
        $data = DB::select('select cpaacc.fas_budget.get_budget_blocking_list(:p_fiscal_year_id,:p_cost_center_dept_id,:p_budget_head_id) from dual',['p_fiscal_year_id'=>$fiscal_year,'p_cost_center_dept_id'=>$department,'p_budget_head_id'=>$budget]);
        return datatables()->of($data)
            ->editColumn('blocked_date',function ($data){
                return HelperClass::dateConvert($data->budget_blocking_date);
            })
            ->editColumn('blocked_amount',function ($data){
                return HelperClass::getCommaSeparatedValue($data->block_amount);
            })
            ->editColumn('unblock_amount',function ($data){
                return HelperClass::getCommaSeparatedValue($data->unblock_amount);
            })
            ->editColumn('remaining_block_amount',function ($data){
                return HelperClass::getCommaSeparatedValue($data->remaining_block_amount);
            })
            ->editColumn('action',function ($data){
                return '<button class="unblock btn btn-primary btn-sm" data-unblockamnt="'.$data->unblock_amount.'" data-blockingid="'.$data->budget_blocking_id.'" data-bamount="'.$data->block_amount.'">Unblock</button>';
            })
            ->rawColumns(['action'])
            ->make();
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        $status_code = sprintf("%4000d","");
        $status_msg = sprintf("%4000s","");
        $params = [
            'p_budget_blocking_id' => $request->post('blocking_id'),
            'p_unblock_amount' => $request->post('unblock_amount'),
            'p_unblock_remarks' => $request->post('unblock_remarks'),
            'p_user_id' => Auth::id(),
            'o_status_code' => &$status_code,
            'o_status_message' => &$status_msg
        ];

        try {
            DB::executeProcedure('fas_budget.fas_budget_unblocking_make',$params);
        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(['response_code'=>99,'response_msg'=>$e->getMessage()]);
        }

        if ($status_code != 1){
            DB::rollBack();
        }else{
            DB::commit();
        }
        return response()->json(['response_code'=>$status_code,'response_msg'=>$status_msg]);
    }
}
