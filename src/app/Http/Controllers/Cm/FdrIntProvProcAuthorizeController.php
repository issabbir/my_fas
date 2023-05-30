<?php


namespace App\Http\Controllers\Cm;

use App\Contracts\LookupContract;
use App\Entities\Security\User;
use App\Enums\ApprovalStatusView;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Http\Controllers\Controller;
use App\Helpers\HelperClass;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use App\Traits\Security\HasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class FdrIntProvProcAuthorizeController extends Controller
{
    /** @var LookupManager */
    private $lookupManager;

    public function __construct ( LookupContract $lookupManager )
    {
        $this->lookupManager = $lookupManager;
    }

    public function index($filter = null)
    {

        $filterData = isset($filter) ? explode('#',Crypt::decryptString($filter)) : $filter;

        return view('cm.fdr-interest-provision-process-authorize.index', [
            'invTypeList' => $this->lookupManager->getLFdrInvestmentType(),
            'filterData' => $filterData
        ]);
    }

    public function approvalView(Request $request, $id, $filter=null)
    {
        $wkMapId = $request->get('wk_map_id');
        $wkRefStatus = $request->get('wk_ref_status');
        $fiscalYearId = $request->get('fiscal_year_id');
        $filterData = isset($filter) ? explode('#',Crypt::decryptString($filter)) : $filter;

        $intProvList = DB::select("select * from table (CPAACC.fas_cm_trans.get_fdr_provision_process_list(:p_investment_type_id,:p_fiscal_year_id))",[ 'p_investment_type_id' =>$filterData[0], 'p_fiscal_year_id' => $fiscalYearId ]);

        $intProvTransViewList = DB::select("select * from table (CPAACC.fas_cm_trans.get_fdr_provision_trans_preview(:p_investment_type_id,:p_fiscal_year_id))",[ 'p_investment_type_id' =>$filterData[0], 'p_fiscal_year_id' => $fiscalYearId ]);

        return view('cm.fdr-interest-provision-process-authorize.approve_reject', [
            'intProvList' => $intProvList,
            'intProvTransViewList' => $intProvTransViewList,
            'wkMapId' => $wkMapId,
            'wkRefStatus' => $wkRefStatus,
            'provisionMstId' => $id,
            'filter'    =>  $filter
        ]);
    }

    public function searchFdrIntProvProcAuthorize(Request $request)
    {
        $terms = $request->post();
        $user_id = auth()->id();
        $queryResult = [];

        $filteredData = Crypt::encryptString($request->post('inv_type_id').'#'. $request->post('authorization_status'));

        /** All Parameter Filter **/
        $params = [
            'p_investment_type_id' =>  $terms['inv_type_id'] ?  $terms['inv_type_id'] : null,
            'p_workflow_approval_status' => $terms['authorization_status'] ?   $terms['authorization_status'] : null,
            'p_user_id' => $user_id,
            ];

        /** Execute Oracle Function With Params **/
        $sql ="select cpaacc.fas_cm_trans.get_fdr_provision_process_auth_list (:p_investment_type_id,:p_workflow_approval_status,:p_user_id) from dual";
        $queryResult = DB::select($sql, $params);

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

            ->addColumn('action', function ($query) use ($filteredData) {
                return '<a href="' . route('fdr-interest-prov-process-authorize.approval-view', [$query->provision_master_id, 'fiscal_year_id'=>$query->fiscal_year_id, 'wk_map_id'=>$query->workflow_mapping_id, 'wk_ref_status'=>$query->workflow_reference_status,'filter'=>$filteredData]) . '"><i class="bx bx-show cursor-pointer"></i></a>';
            })
            ->rawColumns(['status','action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function approveReject(Request $request, $wkMapId=null, $filter=null) {

        $response = $this->fdr_int_prov_proc_api_approved_rejected($request, $wkMapId);

        $message = $response['o_status_message'];
        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|'.$message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('fdr-interest-prov-process-authorize.index',['filter'=>$filter]);
    }

    private function fdr_int_prov_proc_api_approved_rejected($request, $wkMapId)
    {
        $postData = $request->post();

        DB::beginTransaction();
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'i_workflow_mapping_id' => $wkMapId,
                'i_workflow_master_id' => WorkFlowMaster::CM_FDR_INTEREST_PROVISION_PROCESS,
                'i_reference_table' => WkReferenceTable::FAS_CM_FDR_PROVISION_MASTER,
                'i_reference_key' =>  WkReferenceColumn::PROVISION_MASTER_ID,
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
