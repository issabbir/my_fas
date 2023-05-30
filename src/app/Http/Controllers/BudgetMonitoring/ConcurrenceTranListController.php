<?php


namespace App\Http\Controllers\BudgetMonitoring;

use App\Contracts\BudgetMonitoring\BudgetMonitoringContract;
use App\Contracts\BudgetMonitoring\BudgetMonitoringLookupContract;
use App\Contracts\LookupContract;
use App\Entities\BudgetMonitoring\FasBudgetBookingDocs;
use App\Enums\ApprovalStatus;
use App\Enums\ApprovalStatusView;
use App\Enums\BudgetMonitoring\BmnFunctionType;
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

class ConcurrenceTranListController extends Controller
{
    use HasPermission;

    protected $commonManager;

    /** @var LookupManager */
    private $lookupManager;

    /** @var BudgetMonitoringLookupManager */
    private $budgetMonitoringLookupManager;

    public function __construct(LookupContract $lookupManager, BudgetMonitoringLookupContract $budgetMonitoringLookupManager, CommonManager $commonManager)
    {
        $this->lookupManager = $lookupManager;
        $this->budgetMonitoringLookupManager = $budgetMonitoringLookupManager;
        $this->commonManager = $commonManager;
    }


    public function index($filter = null)
    {
        $filterData = isset($filter) ? explode('#', Crypt::decryptString($filter)) : $filter;

        return view('budget-monitoring.concurrence-transaction-listing.index', [
            'CurrentFinancialYearList' => $this->budgetMonitoringLookupManager->getCurrentFinancialYear(),
            'lBillSecList' => $this->budgetMonitoringLookupManager->getBillSections(BmnFunctionType::BUDGET_BOOKING),
            'vendorList' => $this->budgetMonitoringLookupManager->getVendors(),
            'lTenderType' => $this->budgetMonitoringLookupManager->getTenderTypes(),
            'filterData' => $filterData
        ]);
    }

    public function view(Request $request, $id,$filter=null)
    {
        $concurrenceTranInfo = DB::selectOne("select cpaacc.fas_budget.get_budget_booking_trans_view (:p_budget_booking_id,:p_transaction_type) from dual", ['p_budget_booking_id' => $id, 'p_transaction_type' => 'M']);

        $budgetBookingDocsList = FasBudgetBookingDocs::where('budget_booking_id', $id)->get();

        return view('budget-monitoring.concurrence-transaction-listing.view', [
            'concurrenceTranInfo' => $concurrenceTranInfo,
            'budgetBookingDocsList' => $budgetBookingDocsList,
            'vendorList' => $this->budgetMonitoringLookupManager->getVendors(),
            'filter' =>$filter,
            'lBillSecList' => $this->lookupManager->getBillSections(null),
        ]);
    }

    public function searchConcurrenceTransaction(Request $request)
    {
        //$terms = $request->post();
        $fiscalYear = $request->post('fiscal_year_id', null);
        $functionType = $request->post('function_type', null);
        $billSec = $request->post('bill_sec_id', null);
        $queryResult = [];
        $filteredData = Crypt::encryptString($fiscalYear .'#'.$functionType .'#'. $request->post('approval_status'));

        /** All Parameter Filter **/
        /*$params = [
            'p_fiscal_year_id' =>  $terms['fiscal_year_id'] ?  $terms['fiscal_year_id'] : null,
            'p_cost_center_dept_id' =>  $terms['department_id'] ?  $terms['department_id'] : null,
            'p_trans_date' =>  $terms['transaction_date_field'] ?   HelperClass::dateFormatForDB($terms['transaction_date_field']) : null,
            'p_bill_sec_id' =>  $terms['bill_sec_id'] ?  $terms['bill_sec_id'] : null,
            'p_bill_reg_id' =>  $terms['bill_reg_id'] ?  $terms['bill_reg_id'] : null,
            'p_vendor_id' =>  $terms['vendor_id'] ?  $terms['vendor_id'] : null,
            'p_workflow_approval_status' =>  $terms['authorization_status'] ?  $terms['authorization_status'] : null,
            ];*/

        $params = [
            'p_fiscal_year_id' => $fiscalYear,
            'p_bill_sec_id' => $billSec,
            'p_transaction_type' => $functionType,
            'p_workflow_approval_status' => $request->post('approval_status', null),
            'p_user_id'=>Auth::user()->user_id,
        ];

   /** Execute Oracle Function With Params **/
        $sql = "select cpaacc.fas_budget.get_budget_booking_trans_list (:p_fiscal_year_id,:p_bill_sec_id,:p_transaction_type,:p_workflow_approval_status,:p_user_id) from dual";
        $queryResult = DB::select($sql, $params);

        return datatables()->of($queryResult)
            ->editColumn('status', function ($query) {
                if ($query->workflow_approval_status == ApprovalStatus::PENDING) {
                    return '<span class="badge badge-primary badge-pill">' . ApprovalStatusView::PENDING . '</span>';
                } else if ($query->workflow_approval_status == ApprovalStatus::APPROVED) {
                    return '<span class="badge badge-success badge-pill">' . ApprovalStatusView::APPROVED . '</span>';
                } else {
                    return '<span class="badge badge-danger badge-pill">' . ApprovalStatusView::REJECTED . '</span>';
                }
            })
            ->editColumn('document_date', function ($query) {
                return HelperClass::dateConvert($query->document_date);
            })
            ->editColumn('estimate_amount', function ($query) {
                return HelperClass::getCommaSeparatedValue($query->estimate_amount);
            })
            ->editColumn('budget_booking_amount', function ($query) {
                return HelperClass::getCommaSeparatedValue($query->budget_booking_amount);
            })
            ->addColumn('action', function ($query) use ($fiscalYear, $functionType, $filteredData) {
                //return '<a href="' . route('concurrence-transaction-list.view', [$query->budget_booking_id]) . '"><i class="bx bx-show cursor-pointer"></i></a> | <a href="'.route('concurrence-transaction.edit',['booking_id'=>$query->budget_booking_id]).'"><i class="bx bx-edit"></i></a>';
                $action = '';
                $print = '';
                if ($query->workflow_approval_status == ApprovalStatus::PENDING) {
                    $action .= '| <a href="' . route('concurrence-transaction.edit', ['booking_id' => $query->budget_booking_id, 'mode' => Crypt::encryptString('M'),'filter'=>$filteredData]) . '"><i class="bx bx-edit"></i></a>';
                } elseif ($query->workflow_approval_status == ApprovalStatus::APPROVED) {
                    $action .= '| <a href="' . route('concurrence-transaction.edit', ['booking_id' => $query->budget_booking_id, 'mode' => Crypt::encryptString('E'),'filter'=>$filteredData]) . '"><i class="bx bx-edit"></i></a>';
                    $action .= '| <a href="' . route('concurrence-transaction.edit', ['booking_id' => $query->budget_booking_id, 'mode' => Crypt::encryptString('D'),'filter'=>$filteredData]) . '"><i class="bx bx-trash"></i></a>';
                }
                if ($functionType == \App\Enums\ApprovalStatus::MAKE) {
                    $print .= '| <a  target="_blank" href="' . request()->root() . '/report/render/budget_concurrence_details?xdo=/~weblogic/FAS_NEW/BUDGET_MODULE/Budget_Monitoring/RPT_BUDGET_CONCURRENCE.xdo&p_fiscal_year_id=' . $fiscalYear . '&p_budget_booking_id=' . $query->budget_booking_id . '&type=pdf&filename=budget_concurrence_details"><i class="bx bx-printer cursor-pointer"></i></a>';
                }
                return '<a href="' . route('concurrence-transaction-list.view', ['id'=>$query->budget_booking_id,'filter'=>$filteredData]) . '"><i class="bx bx-show cursor-pointer"></i></a> ' . $action . ' ' . $print;
            })
            ->rawColumns(['action', 'document_date', 'status'])
            ->addIndexColumn()
            ->make(true);
    }
}
