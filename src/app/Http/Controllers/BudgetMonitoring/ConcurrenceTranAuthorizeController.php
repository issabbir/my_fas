<?php


namespace App\Http\Controllers\BudgetMonitoring;

use App\Contracts\BudgetMonitoring\BudgetMonitoringContract;
use App\Contracts\BudgetMonitoring\BudgetMonitoringLookupContract;
use App\Contracts\LookupContract;
use App\Entities\BudgetMonitoring\FasBudgetBookingDocs;
use App\Enums\ApprovalStatus;
use App\Enums\ApprovalStatusView;
use App\Enums\BudgetMonitoring\BmnFunctionType;
use App\Enums\WkReferenceColumn;
use App\Enums\WkReferenceTable;
use App\Enums\WorkFlowMaster;
use App\Http\Controllers\Controller;
use App\Helpers\HelperClass;
use App\Managers\BudgetMonitoring\BudgetMonitoringLookupManager;
use App\Managers\Common\CommonManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;
use App\Traits\Security\HasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConcurrenceTranAuthorizeController extends Controller
{
    use HasPermission;
    protected  $commonManager;

    /** @var LookupManager */
    private $lookupManager;

    /** @var BudgetMonitoringLookupManager */
    private $budgetMonitoringLookupManager;

    public function __construct(LookupContract $lookupManager,  BudgetMonitoringLookupContract $budgetMonitoringLookupManager, CommonManager $commonManager)
    {
        $this->lookupManager = $lookupManager;
        $this->budgetMonitoringLookupManager = $budgetMonitoringLookupManager;
        $this->commonManager = $commonManager;
    }

    public function index($filter = null)
    {
        $filterData = isset($filter) ? explode('#',Crypt::decryptString($filter)) : $filter;

        return view('budget-monitoring.concurrence-transaction-authorize.index', [
            'CurrentFinancialYearList' => $this->budgetMonitoringLookupManager->getCurrentFinancialYear(),
            'lBillSecList' => $this->budgetMonitoringLookupManager->getBillSections(BmnFunctionType::BUDGET_BOOKING),
            'vendorList' => $this->budgetMonitoringLookupManager->getVendors(),
            'filterData' => $filterData
        ]);
    }

    public function approvalView(Request $request)
    {
        $wkMapId = $request->get('wk_map_id');
        $wkRefStatus = $request->get('wk_ref_status');

        $concurrenceTranInfo = DB::selectOne("select cpaacc.fas_budget.get_budget_booking_trans_view (:p_budget_booking_id,:p_transaction_type) from dual", ['p_budget_booking_id' => $request->get('booking_id'),'p_transaction_type'=> Crypt::decryptString($request->get('mode'))]);
        //dd($concurrenceTranInfo);
        $budgetBookingDocsList = FasBudgetBookingDocs::where('budget_booking_id', $request->get('booking_id'))->get();

        return view('budget-monitoring.concurrence-transaction-authorize.approval_view', [
            'concurrenceTranInfo' => $concurrenceTranInfo,
            'budgetBookingDocsList' => $budgetBookingDocsList,
            'vendorList' => $this->budgetMonitoringLookupManager->getVendors(),
            'wkMapId' => $wkMapId,
            'wkRefStatus' => $wkRefStatus,
            'mode' => $request->get('mode'),
            'filter' => $request->get('filter')
        ]);
    }

    public function searchConcurrenceTransactionAuth(Request $request)
    {
        $terms = $request->post();
        $fiscalYear = $terms['fiscal_year_id'];
        $userId = auth()->id();
        $queryResult = [];
        $mode = $terms['auth_function_type'];
        $billSec = $request->post('bill_sec_id', null);
        /** All Parameter Filter **/
        $params = [
            'p_fiscal_year_id' =>  $terms['fiscal_year_id'] ?  $terms['fiscal_year_id'] : null,
            'p_bill_sec_id' => $billSec,
            'p_transaction_type' =>  $mode ?  $mode : null,
            'p_workflow_approval_status' =>  $terms['approval_status'] ?  $terms['approval_status'] : null,
            'p_user_id' =>   $userId,
            /*'p_cost_center_dept_id' =>  $terms['department_id'] ?  $terms['department_id'] : null,
            'p_trans_date' =>  $terms['transaction_date_field'] ?   HelperClass::dateFormatForDB($terms['transaction_date_field']) : null,
            'p_bill_sec_id' =>  $terms['bill_sec_id'] ?  $terms['bill_sec_id'] : null,
            'p_bill_reg_id' =>  $terms['bill_reg_id'] ?  $terms['bill_reg_id'] : null,
            'p_vendor_id' =>  $terms['vendor_id'] ?  $terms['vendor_id'] : null,*/
            ];

        /** Execute Oracle Function With Params **/
        //$sql ="select cpaacc.fas_budget.get_budget_booking_auth_list (:p_fiscal_year_id,:p_cost_center_dept_id,:p_trans_date,:p_bill_sec_id,:p_bill_reg_id,:p_vendor_id,:p_workflow_approval_status,:p_user_id) from dual";
        $sql ="select cpaacc.fas_budget.get_budget_booking_auth_list (:p_fiscal_year_id,:p_bill_sec_id,:p_transaction_type,:p_workflow_approval_status,:p_user_id) from dual";
        $queryResult = DB::select($sql, $params);
        $filteredData = Crypt::encryptString($request->post('fiscal_year_id') .'#'.$request->post('auth_function_type') .'#'. $request->post('approval_status'));

        //dd($queryResult);
        return datatables()->of($queryResult)
            ->editColumn('document_date', function ($query) {
                return HelperClass::dateConvert($query->document_date);
            })
            ->editColumn('estimate_amount', function ($query) {
                return HelperClass::getCommaSeparatedValue($query->estimate_amount);
            })
            ->editColumn('budget_booking_amt', function ($query) {
                return HelperClass::getCommaSeparatedValue($query->budget_booking_amt);
            })
            ->editColumn('status', function($query) {
                if($query->workflow_approval_status == ApprovalStatus::PENDING){
                    return '<span class="badge badge-primary badge-pill">'.ApprovalStatusView::PENDING.'</span>';
                } else if ($query->workflow_approval_status == ApprovalStatus::APPROVED) {
                    return '<span class="badge badge-success badge-pill">'.ApprovalStatusView::APPROVED.'</span>';
                } else {
                    return '<span class="badge badge-danger badge-pill">'.ApprovalStatusView::REJECTED.'</span>';
                }
            })
            ->addColumn('action', function ($query) use ($fiscalYear, $mode, $filteredData) {
                $approveBtn = '';
                if($query->workflow_approval_status == ApprovalStatus::PENDING){
                    $approveBtn = '
                            <a href="#" class="approve-reject-btn cursor-pointer" data-map="'.$query->workflow_mapping_id.'" data-mode="'.$mode.'" name="authorize"
                                    value="'.ApprovalStatus::APPROVED.'"><i class="bx bx-check-double bg-success rounded"></i>
                            </a>';
                }

                return $approveBtn.'<a href="' . route('concurrence-transaction-authorization.approval-view', ['booking_id'=>$query->budget_booking_id,'wk_map_id'=>$query->workflow_mapping_id,'wk_ref_status'=>$query->workflow_reference_status,'mode'=>Crypt::encryptString($mode),'filter'=>$filteredData]) . '">
<i class="bx bx-show cursor-pointer"></i></a>
<a  target="_blank" href="'.request()->root().'/report/render/budget_concurrence_details?xdo=/~weblogic/FAS_NEW/BUDGET_MODULE/Budget_Monitoring/RPT_BUDGET_CONCURRENCE.xdo&p_fiscal_year_id='.$fiscalYear.'&p_budget_booking_id='.$query->budget_booking_id.'&type=pdf&filename=budget_concurrence_details">
<i class="bx bx-printer cursor-pointer"></i></a>';
            })
            ->rawColumns(['document_date','status','action'])
            /*->addIndexColumn()*/
            ->make(true);
    }

    public function approveReject(Request $request, $wkMapId=null, $filter = null) {

        $mode = Crypt::decryptString($request->post('mode'));

        $response = $this->concurrence_trans_api_approved_rejected($request, $wkMapId,$mode);

        $message = $response['o_status_message'];
        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|'.$message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('concurrence-transaction-authorization.index',['filter'=>$filter]);
    }

    public function approveBudget(Request $request)
    {
        $response = $this->concurrence_trans_api_approved_rejected($request, $request->post('wk_map_id'),$request->post('mode'));
        return response()->json(['response_msg'=>$response['o_status_message'],'response_code'=>$response['o_status_code']]);
    }

    private function concurrence_trans_api_approved_rejected($request, $wkMapId,$mode)
    {
        $postData = $request->post();

        DB::beginTransaction();
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            switch ($mode){
                case 'E':
                    $masterId = WorkFlowMaster::BUDGET_MON_CONCURRENCE_TRANS_EDIT_AUTHORIZE;
                    $referenceTable = WkReferenceTable::FAS_BUDGET_BOOKING_TRANS_LOG;
                    $referenceColumn = WkReferenceColumn::BUDGET_BOOK_TRAN_LOG_ID;
                    break;
                case 'D':
                    $masterId = WorkFlowMaster::BUDGET_MON_CONCURRENCE_TRANS_DELETE_AUTHORIZE;
                    $referenceTable = WkReferenceTable::FAS_BUDGET_BOOKING_TRANS_LOG;
                    $referenceColumn = WkReferenceColumn::BUDGET_BOOK_TRAN_LOG_ID;
                    break;
                default:
                    $masterId = WorkFlowMaster::BUDGET_MON_BUDGET_CONCURRENCE_TRANSACTION_APPROVAL;
                    $referenceTable = WkReferenceTable::FAS_BUDGET_BOOKING_TRANS;
                    $referenceColumn = WkReferenceColumn::BUDGET_BOOKING_ID;
            }

            $params = [
                'i_workflow_mapping_id' => $wkMapId,
                'i_workflow_master_id' => $masterId,
                'i_reference_table' => $referenceTable,
                'i_reference_key' =>  $referenceColumn,
                'i_reference_status' => $postData['approve_reject_value'],
                'i_reference_comment' => isset($postData['comment_on_decline']) ? $postData['comment_on_decline'] : null,
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
