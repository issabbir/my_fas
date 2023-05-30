<?php
/**
 *Created by PhpStorm
 *Created at ৩/৬/২১ ১২:১৭ PM
 */

namespace App\Http\Controllers\Ar;


use App\Contracts\Ap\ApLookupContract;
use App\Contracts\Ar\ArLookupContract;
use App\Entities\Common\LGlIntegrationFunctions;
use App\Entities\Gl\CalendarDetail;
use App\Entities\Gl\LPeriodClosingEvent;
use App\Entities\Security\Report;
use App\Enums\Common\LGlInteModules;
use App\Enums\Gl\CalendarStatus;
use App\Enums\ModuleInfo;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Ar\ArLookupManager;
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

    public function __construct(LookupManager $lookupManager, ArLookupManager $arLookupManager)
    {
        $this->lookupManager = $lookupManager;
        $this->calenderDetail = new CalendarDetail();
        $this->functions = new LGlIntegrationFunctions();
        $this->closingEvent = new LPeriodClosingEvent();
        $this->arLookupManager = $arLookupManager;
    }

    public function index(Request $request)
    {
        $module = ModuleInfo::AR_MODULE_ID;
        $reportObj = new Report();

        if (auth()->user()->hasGrantAll()) {
            $reports = $reportObj->where(['module_id'=>$module, 'active_yn'=>'Y'])->orderBy('report_name', 'ASC')->get();
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

        return view('ar.reportgenerator.index', compact('reports'));
    }

    public function reportParams($id)
    {
        $report = Report::find($id);
        $subsidiary_type = DB::select("select cpaacc.FAS_REPORT_CONTROL.get_gl_subsidiary_name(".LGlInteModules::ACCOUNT_RECEIVABLE.") from dual");
        $modules = $this->lookupManager->getARModuleList();
        $fiscalYear = DB::select("select cpaacc.FAS_REPORT_CONTROL.get_financial_years() from dual");
        $arFuncType = $this->functions->where("module_id", "=", LGlInteModules::ACCOUNT_RECEIVABLE)->where("function_parent_id", "=", null)->orderBy("function_id", "ASC")->get();
        $department = $this->lookupManager->getDeptCostCenter();
        $closingEvents = $this->closingEvent->get();
        $billSecs = $this->lookupManager->findLBillSec();
        $users = DB::select("select distinct su.user_name, su.user_id, emp.emp_name
                                        from cpaacc.workflow_template wt
                                        join cpa_security.sec_role secr on wt.step_role_key = secr.role_key
                                        join cpa_security.sec_user_roles secur on secur.role_id = secr.role_id
                                        join cpa_security.sec_users su on su.user_id = secur.user_id
                                        join pmis.employee emp on emp.emp_id = su.emp_id
                                        order by emp.emp_name asc");

        //Write your dependency elements query here
        return view('ar.reportgenerator.report-params', compact('users','billSecs','fiscalYear','modules','arFuncType', 'subsidiary_type', 'closingEvents', 'department', 'report'))->render();
    }
}
