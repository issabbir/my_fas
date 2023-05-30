<?php
/**
 *Created by PhpStorm
 *Created at ৩/৬/২১ ১২:১৭ PM
 */

namespace App\Http\Controllers\BudgetMonitoring;


use App\Contracts\Ap\ApLookupContract;
use App\Contracts\Ar\ArLookupContract;
use App\Entities\Common\LBudgetType;
use App\Entities\Common\LGlIntegrationFunctions;
use App\Entities\Gl\CalendarDetail;
use App\Entities\Gl\LPeriodClosingEvent;
use App\Entities\Security\Report;
use App\Enums\BudgetMonitoring\BmnFunctionType;
use App\Enums\Common\LGlInteModules;
use App\Enums\Gl\CalendarStatus;
use App\Enums\ModuleInfo;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Ar\ArLookupManager;
use App\Managers\BudgetMonitoring\BudgetMonitoringLookupManager;
use App\Managers\LookupManager;
use Illuminate\Http\Request;
use App\Traits\Security\HasPermission;
use Illuminate\Support\Facades\DB;

class ReportGeneratorController extends Controller
{
    use HasPermission;

    protected $lookupManager;
    protected $calenderDetail;
    protected $functions;
    protected $closingEvent;
    protected $arLookupManager;
    private $monitoringManager;

    public function __construct(LookupManager $lookupManager, ArLookupManager $arLookupManager)
    {
        $this->lookupManager = $lookupManager;
        $this->calenderDetail = new CalendarDetail();
        $this->functions = new LGlIntegrationFunctions();
        $this->closingEvent = new LPeriodClosingEvent();
        $this->arLookupManager = $arLookupManager;
        $this->monitoringManager = new BudgetMonitoringLookupManager();
    }

    public function index(Request $request)
    {

        $module = ModuleInfo::BUDGET_MON_MODULE_ID;

        $reportObject = new Report();

        if (auth()->user()->hasGrantAll()) {
            $reports = $reportObject->where(['module_id'=>$module, 'active_yn'=>'Y'])->orderBy('report_name', 'ASC')->get();
        } else {
            $roles = auth()->user()->getRoles();
            $reports = array();
            foreach ($roles as $role) {
                if (count($role->reports)) {
                    //$rpts = $role->reports->where(['module_id'=>$module, 'active_yn'=>'Y']);
                    $rpts = $role->reports->where('module_id',$module)->where('active_yn','Y');
                    foreach ($rpts as $report) {
                        $reports[$report->report_id] = $report;
                    }
                }
            }
            //Sorted the list according to name.
            $array_column = array_column($reports, 'report_name');
            array_multisort($array_column, SORT_ASC, $reports);
        }

        return view('budget-monitoring.reportgenerator.index', compact('reports'));
    }

    public function reportParams($id)
    {

        $modules = $this->lookupManager->getARModuleList();
        //$subsidiary_type = $this->lookupManager->findPartySubLedger(LGlInteModules::ACCOUNT_RECEIVABLE);
        $subsidiary_type = $this->lookupManager->findArPartySubLedger(LGlInteModules::ACCOUNT_RECEIVABLE);
        $postingPeriod = CalendarDetail::where('posting_period_status', CalendarStatus::OPENED)->first();

        $postingDate = [
            'minDate' => HelperClass::previousMonth($postingPeriod->posting_period_beg_date),
            'maxDate' => HelperClass::previousMonth($postingPeriod->posting_period_end_date)
        ];

        $customers = $this->arLookupManager->getCustomers();
        //$postingPeriod = $this->lookupManager->findPostingPeriod();

        $oldPostingPeriods = $this->calenderDetail->select('posting_period_display_name', 'calendar_detail_id')->where('posting_period_beg_date', '<', function ($q) {
            $q->select('posting_period_beg_date')->from('fas_calendar_detail')->where('posting_period_status', '=', 'O');
        })->orderBy('posting_period_beg_date', 'DESC')->get();

        $arFuncType = $this->functions->where("module_id", "=", LGlInteModules::ACCOUNT_RECEIVABLE)->where("function_parent_id", "=", null)->orderBy("function_id", "ASC")->get();

        $closingEvents = $this->closingEvent->get();
        $fiscalYear = DB::select("select cpaacc.FAS_REPORT_CONTROL.get_financial_years() from dual");

        //Write your dependency elements query here
        $report = Report::find($id);
        $lCostCenterDpt = $this->lookupManager->getDeptCostCenter();
        $billSecs = $this->lookupManager->findLBillSec();
        $budgetBillSections = $this->monitoringManager->getBillSections(BmnFunctionType::BUDGET_BOOKING);
        $lBudgetType = LBudgetType::all();
        $subsidiary_type = DB::select("select cpaacc.FAS_REPORT_CONTROL.get_gl_subsidiary_name(" . LGlInteModules::ACC_PAY_VENDOR . ") from dual");

        //return view('budget-monitoring.reportgenerator.report-params', compact('modules','postingDate','arFuncType', 'customers', 'subsidiary_type', 'closingEvents', 'department', 'report', 'postingPeriod', 'oldPostingPeriods'))->render();
        return view('budget-monitoring.reportgenerator.report-params', compact( 'budgetBillSections','lCostCenterDpt','fiscalYear','billSecs','lBudgetType', 'report', 'subsidiary_type' ))->render();
    }
}
