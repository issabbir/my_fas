<?php


namespace App\Http\Controllers\Cm;

use App\Contracts\LookupContract;
use App\Entities\Security\User;
use App\Entities\WorkFlowMapping;
use App\Enums\ApprovalStatus;
use App\Enums\ApprovalStatusView;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Enums\WorkFlowRoleKey;
use App\Http\Controllers\Controller;
use App\Helpers\HelperClass;
use App\Managers\Common\CommonManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use App\Traits\Security\HasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class FdrInvRegAuthorizeController extends Controller
{
    use HasPermission;
    protected  $commonManager;

    /** @var LookupManager */
    private $lookupManager;

    public function __construct(LookupContract $lookupManager, CommonManager $commonManager)
    {
        $this->lookupManager = $lookupManager;
        $this->commonManager = $commonManager;
    }


    public function index($filter = null)
    {

        $filterData = isset($filter) ? explode('#',Crypt::decryptString($filter)) : $filter;

        return view('cm.fdr-investment-register-authorize.index', [
            'invTypeList' => $this->lookupManager->getLFdrInvestmentType(),
            'filterData' => $filterData
        ]);
    }

    public function approvalView(Request $request, $id, $filter=null)
    {
        $wkMapId = $request->get('wk_map_id');
        $userId = $request->get('user_id');
        $wkRefStatus = $request->get('wk_ref_status');

        $fdrInvInfo = DB::selectOne("select cpaacc.fas_cm_config.get_fdr_investment_auth_view (:p_investment_auth_log_id) from dual",['p_investment_auth_log_id' => $id]);

        return view('cm.fdr-investment-register-authorize.approve_reject', [
            'fdrInvInfo' => $fdrInvInfo,
            'wkMapId' => $wkMapId,
            'wkRefStatus' => $wkRefStatus,
            'filter'    =>  $filter
        ]);
    }

    public function searchFdrInvRegAuthorize(Request $request)
    {
        $terms = $request->post();
        $user_id = auth()->id();
        //dd($user_id);
        $queryResult = [];
        $filteredData = Crypt::encryptString($request->post('inv_type_id') .'#'.$request->post('bank_id') .'#'. $request->post('branch_id') .'#'. $request->post('action_type') .'#'. $request->post('authorization_status'));

        /** All Parameter Filter **/
        $params = [
            'p_investment_type_id' =>  $terms['inv_type_id'] ?  $terms['inv_type_id'] : null,
            'p_bank_code' =>  $terms['bank_id'] ?   $terms['bank_id'] : null,
            'p_branch_code' =>  $terms['branch_id'] ?   $terms['branch_id'] : null,
            'p_action_type' =>  $terms['action_type'] ?   $terms['action_type'] : null,
            'p_workflow_approval_status' => $terms['authorization_status'] ?   $terms['authorization_status'] : null,
            'p_user_id' => $user_id,
            ];

        /** Execute Oracle Function With Params **/
        $sql ="select cpaacc.fas_cm_config.get_fdr_investment_auth_list (:p_investment_type_id,:p_bank_code,:p_branch_code,:p_action_type,:p_workflow_approval_status,:p_user_id) from dual";
        $queryResult = DB::select($sql, $params);

        //dd($terms, $queryResult);


        return datatables()->of($queryResult)
            ->editColumn('status', function($query) {
                if($query->approval_status == ApprovalStatusView::PENDING){
                    return '<span class="badge badge-primary badge-pill">'.ApprovalStatusView::PENDING.'</span>';
                } else if ($query->approval_status == ApprovalStatusView::APPROVED) {
                    return '<span class="badge badge-success badge-pill">'.ApprovalStatusView::APPROVED.'</span>';
                } else if ($query->approval_status == ApprovalStatusView::REJECTED) {
                    return '<span class="badge badge-danger badge-pill">'.ApprovalStatusView::REJECTED.'</span>';
                } else {
                    return '<span class="badge badge-warning badge-pill">'.ApprovalStatusView::FORWARDED.'</span>';
                }
            })
            ->editColumn('investment_date',function ($query){
                return HelperClass::dateConvert($query->investment_date);
            })
            ->editColumn('maturity_date',function ($query){
                return HelperClass::dateConvert($query->maturity_date);
            })
            ->editColumn('investment_amount',function ($query){
                return HelperClass::getCommaSeparatedValue($query->investment_amount);
            })
            ->editColumn('action_type', function ($data) {
                if ($data->action_type == 'I' ) {
                    return 'New';
                } else if ($data->action_type == 'U') {
                    return 'Edit';
                }
            })
            ->addColumn('action', function ($query) use ($filteredData) {
                /*return '<button class="btn btn-primary btn-sm trans-mst"  id="'.$query->payment_id.'">Detail View</button>';
                <a class="btn btn-sm btn-info"  href="' . route('invoice-bill-payment.view', [$query->payment_id]) . '"><i class="bx bx-show cursor-pointer"></i> View</a>*/
                return '<a href="' . route('fdr-investment-register-authorize.approval-view', [$query->investment_auth_log_id,'wk_map_id'=>$query->workflow_mapping_id, 'user_id'=>$query->login_user_id, 'wk_ref_status'=>$query->workflow_reference_status,'filter'=>$filteredData]) . '"><i class="bx bx-show cursor-pointer"></i></a>';
            })
            ->rawColumns(['status','action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function approveReject(Request $request, $wkMapId=null, $filter=null) {

        $response = $this->fdr_inv_reg_api_approved_rejected($request, $wkMapId);

        $message = $response['o_status_message'];
        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|'.$message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('fdr-investment-register-authorize.index',['filter'=>$filter]);
    }

    private function fdr_inv_reg_api_approved_rejected($request, $wkMapId)
    {
        $postData = $request->post();
        //dd($request);

        DB::beginTransaction();
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'i_workflow_mapping_id' => $wkMapId,
                'i_workflow_master_id' => WorkFlowMaster::CM_FDR_INVESTMENT_REGISTER,
                'i_reference_table' => WkReferenceTable::FAS_CM_FDR_INVESTMENT_AUTH_LOG,
                'i_reference_key' =>  WkReferenceColumn::INVESTMENT_AUTH_LOG_ID,
                'i_reference_status' => $postData['approve_reject_value'],
                'i_reference_comment' => $postData['comment_on_decline'],
                'i_user_id' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];

            DB::executeProcedure('CPAACC.WORKFLOW_APPROVAL_ENTRY', $params);
            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }
        }
        catch (\Exception $e) {
            DB::rollBack();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        DB::commit();
        return $params;
    }

}
