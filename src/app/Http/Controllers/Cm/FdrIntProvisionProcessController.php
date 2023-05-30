<?php


namespace App\Http\Controllers\Cm;

use App\Contracts\LookupContract;
use App\Entities\Security\User;
use App\Enums\ActionType;
use App\Enums\ApprovalStatusView;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
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

class FdrIntProvisionProcessController extends Controller
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

    public function index()
    {
        return view('cm.fdr-interest-provision-process.index', [
            'invTypeList' => $this->lookupManager->getLFdrInvestmentType(),
            'fiscalYear' => $this->lookupManager->getCurrentFinancialYear(),
        ]);
    }

    public function editView(Request $request, $id)
    {
        $invTypeId = $request->get('inv_type_id');
        $fiscalYearId = $request->get('fiscal_year_id');
        $approvalStatus = $request->get('approval_status');

        $intProvTransViewList = DB::select("select * from table (CPAACC.fas_cm_trans.get_fdr_provision_trans_preview(:p_investment_type_id,:p_fiscal_year_id))",[ 'p_investment_type_id' =>$invTypeId, 'p_fiscal_year_id' => $fiscalYearId ]);

        return view('cm.fdr-interest-provision-process.index', [
            'provMstId' => $id,
            'invTypeId' => $invTypeId,
            'fiscalYearId' => $fiscalYearId,
            'approvalStatus' => $approvalStatus,
            'intProvTransViewList' => $intProvTransViewList,
            'invTypeList' => $this->lookupManager->getLFdrInvestmentType(),
            'fiscalYear' => $this->lookupManager->getCurrentFinancialYear(),

        ]);
    }

    public function dataTableList(Request $request)
    {
        $terms = $request->post();
        $queryResult = [];

        /** All Parameter Filter **/
        $params = [
            'p_investment_type_id' =>  $terms['inv_type_id'] ?  $terms['inv_type_id'] : null,
        ];

        /** Execute Oracle Function With Params **/
        $sql ="select cpaacc.fas_cm_trans.get_fdr_provision_process_make_list (:p_investment_type_id) from dual";
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
                    return '<span class="badge badge-warning badge-pill">'.ApprovalStatusView::DRAFT.'</span>';
                }
            })

            ->addColumn('action', function ($query) {
                $icon = $query->approval_status == ApprovalStatusView::DRAFT ? 'bx bx-edit' : 'bx bx-show' ;
                return '<a href="' . route('fdr-interest-provision-process.edit-view', [$query->provision_master_id,'inv_type_id'=>$query->investment_type_id,'fiscal_year_id'=>$query->fiscal_year_id, 'approval_status'=>$query->approval_status]) . '"><i class="'.$icon.' cursor-pointer"></i></a>';
            })
            ->rawColumns(['action','status'])
            ->addIndexColumn()
            ->make(true);
    }

    public function store(Request $request)
    {
        $postData = $request->post();
        $actionType = isset($postData['action_type']) && ($postData['action_type'] == ActionType::SAVE) ? ActionType::SAVED : ActionType::SUBMITTED;
        $postingPeriod = $postData['posting_period'];

        $batchId = sprintf("%4000d", "");
        $status_code = sprintf("%4000d", "");
        $status_message = sprintf("%4000s", "");
        $provision_master_id = '';

        try {
            DB::beginTransaction();



            $params = [
                'p_investment_type_id' => isset($postData['inv_type_id']) ? $postData['inv_type_id'] : null,
                'p_fiscal_year_id' => $postData['fiscal_year'],
                'p_trans_period_id' => $postingPeriod,
                /*'p_trans_provision_flag' => $actionType,*/
                'p_user_id' => auth()->id(),
                'o_provision_master_id' => [
                    'value' => &$provision_master_id,
                    'type' => \PDO::PARAM_INT,
                    'length' => 255
                ],
                'o_trans_batch_id' => &$batchId,
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];

            DB::executeProcedure('cpaacc.fas_cm_trans.fas_fdr_provision_process_make', $params);

            if ( $params['o_status_code'] != 1 ) {
                DB::rollBack();
                //return $params;
                return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);
            }

            $oProvMstId = isset($params['o_provision_master_id']['value']) ? $params['o_provision_master_id']['value'] : '';

            if ( ($actionType == ActionType::SUBMITTED) && $oProvMstId ) {

                $wk_mapping_status_code = sprintf("%4000s", "");
                $wk_mapping_status_message = sprintf("%4000s", "");

                $wkMappingParams = [
                    'P_WORKFLOW_MASTER_ID' => WorkFlowMaster::CM_FDR_INTEREST_PROVISION_PROCESS,
                    'P_REFERENCE_TABLE' => WkReferenceTable::FAS_CM_FDR_PROVISION_MASTER,
                    'P_REFERANCE_KEY' => WkReferenceColumn::PROVISION_MASTER_ID,
                    'P_REFERANCE_ID' => $oProvMstId,
                    'P_TRANS_PERIOD_ID' => $postingPeriod,
                    'P_INSERT_BY' => auth()->id(),
                    'o_status_code' => &$wk_mapping_status_code,
                    'o_status_message' => &$wk_mapping_status_message,
                ];

                //dd($wkMappingParams);

                DB::executeProcedure('CPAACC.WORKFLOW_MAPPING_ENTRY', $wkMappingParams);

                if ($wkMappingParams['o_status_code'] != 1) {
                    DB::rollBack();
                    //return $wkMappingParams;
                    return response()->json(["response_code" => $wk_mapping_status_code, "response_msg" => $wk_mapping_status_message]);
                }
            }

        } catch (\Exception $e) {
            DB::rollBack();
            //return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
            return response()->json(["response_code" => '99', 'response_msg' => $e->getMessage()]);
        }
        DB::commit();
        //return $params;
        //return response()->json(["response_code" => $status_code, "response_msg" => $status_message]);

        //Sujon-CR
        return response()->json(["response_code" => $status_code, "response_msg" => $status_message, "batch" => $batchId, "period" =>$postingPeriod ]);
        //return response()->json(["response_code" => $status_code, "response_msg" => $status_message ]);

    }

}
