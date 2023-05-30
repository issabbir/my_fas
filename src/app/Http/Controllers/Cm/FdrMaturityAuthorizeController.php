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

class FdrMaturityAuthorizeController extends Controller
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

        return view('cm.fdr-maturity-authorize.index', [
            'invTypeList' => $this->lookupManager->getLFdrInvestmentType(),
            'fiscalYear' => $this->lookupManager->getCurrentFinancialYear(),
            'filterData' => $filterData
        ]);
    }

    public function approvalView(Request $request, $id, $filter=null)
    {
        $wkMapId = $request->get('wk_map_id');
        $wkRefStatus = $request->get('wk_ref_status');
        //$filterData = isset($filter) ? explode('#',Crypt::decryptString($filter)) : $filter;

        $fdrMaturityTransView = DB::selectOne("select cpaacc.fas_cm_trans.get_fdr_maturity_trans_view (:p_maturity_trans_id) from dual",['p_maturity_trans_id' => $id]);

        $fdrContraView = DB::selectOne("select cpaacc.fas_cm_trans.get_fdr_investment_contra_ac (:p_investment_type_id) from dual",['p_investment_type_id' => $fdrMaturityTransView->investment_type_id]);

        $contraBankAccountInfo = DB::selectOne("select CPAACC.fas_gl_trans.get_gl_account_info (:p_gl_acc_id) from dual", ['p_gl_acc_id' => $fdrContraView->gl_acc_id]);

        $fdrMaturityTransViewList = DB::select("select cpaacc.fas_cm_trans.get_gl_transaction_view (:p_gl_trans_master_id) from dual",['p_gl_trans_master_id' => $fdrMaturityTransView->gl_trans_master_id]);

        $fdrMaturityViewSplitList = DB::select("select cpaacc.fas_cm_trans.get_fdr_investment_split_list_view (:p_maturity_trans_id) from dual",['p_maturity_trans_id' => $id]);
        //dd($fdrMaturityTransView, $fdrContraView,$fdrMaturityViewSplitList);

        return view('cm.fdr-maturity-authorize.approve_reject', [
            'fdrMaturityTransView' => $fdrMaturityTransView,
            'fdrContraView' => $fdrContraView,
            'contraBankAccountInfo' => $contraBankAccountInfo,
            'fdrMaturityTransViewList' => $fdrMaturityTransViewList,
            'fdrMaturityViewSplitList' => $fdrMaturityViewSplitList,
            'wkMapId' => $wkMapId,
            'wkRefStatus' => $wkRefStatus,
            //'provisionMstId' => $id,
            'filter'    =>  $filter
        ]);
    }

    public function searchFdrIntProvProcAuthorize(Request $request)
    {
        $terms = $request->post();
        $user_id = auth()->id();
        $queryResult = [];

        $filteredData = Crypt::encryptString($request->post('inv_type_id').'#'. $request->post('fiscal_year').'#'. $request->post('period').'#'. $request->post('authorization_status'));

        /** All Parameter Filter **/
        $params = [
            'p_investment_type_id' =>  $terms['inv_type_id'] ?  $terms['inv_type_id'] : null,
            'p_fiscal_year_id' => $terms['fiscal_year'] ?   $terms['fiscal_year'] : null,
            'p_posting_period_id' => $terms['period'] ?   $terms['period'] : null,
            'p_workflow_approval_status' => $terms['authorization_status'] ?   $terms['authorization_status'] : null,
            'p_user_id' => $user_id,
            ];

        /** Execute Oracle Function With Params **/
        $sql ="select cpaacc.fas_cm_trans.get_fdr_maturity_trans_auth_list (:p_investment_type_id,:p_fiscal_year_id,:p_posting_period_id,:p_workflow_approval_status,:p_user_id) from dual";
        $queryResult = DB::select($sql, $params);
        //dd($queryResult);

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
            ->editColumn('posting_date',function ($query){
                return HelperClass::dateConvert($query->posting_date);
            })
            ->addColumn('action', function ($query) use ($filteredData) {
                return '<a href="' . route('fdr-maturity-authorize.approval-view', [$query->maturity_trans_id, 'wk_map_id'=>$query->workflow_mapping_id, 'wk_ref_status'=>$query->workflow_reference_status,'filter'=>$filteredData]) . '"><i class="bx bx-show cursor-pointer"></i></a>';
            })
            ->rawColumns(['status','action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function approveReject(Request $request, $wkMapId=null, $filter=null) {

        $response = $this->fdr_maturity_api_approved_rejected($request, $wkMapId);

        $message = $response['o_status_message'];
        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|'.$message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('fdr-maturity-authorize.index',['filter'=>$filter]);
    }

    private function fdr_maturity_api_approved_rejected($request, $wkMapId)
    {
        $postData = $request->post();

        DB::beginTransaction();
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'i_workflow_mapping_id' => $wkMapId,
                'i_workflow_master_id' => WorkFlowMaster::CM_FDR_MATURITY_TRANSACTION,
                'i_reference_table' => WkReferenceTable::FAS_CM_FDR_MATURITY_TRANS,
                'i_reference_key' =>  WkReferenceColumn::MATURITY_TRANS_ID,
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
