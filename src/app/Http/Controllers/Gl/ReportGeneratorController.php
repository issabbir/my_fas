<?php
/**
 *Created by PhpStorm
 *Created at ৩/৬/২১ ১২:১৭ PM
 */

namespace App\Http\Controllers\Gl;


use App\Entities\Common\LBillRegister;
use App\Entities\Common\LGlIntegrationFunctions;
use App\Entities\Common\LInstrumentType;
use App\Entities\Gl\CalendarDetail;
use App\Entities\Gl\LPeriodClosingEvent;
use App\Entities\Gl\LPeriodType;
use App\Entities\Security\Report;
use App\Enums\Common\LBillSecReg;
use App\Enums\Common\LGlInteModules;
use App\Enums\ModuleInfo;
use App\Http\Controllers\Controller;
use App\Managers\LookupManager;
use App\Traits\Security\HasPermission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportGeneratorController extends Controller
{
    use HasPermission;

    protected $lookupManager;
    protected $calenderDetail;
    protected $functions;
    protected $closingEvent;

    public function __construct(LookupManager $lookupManager)
    {
        $this->lookupManager = $lookupManager;
        $this->calenderDetail = new CalendarDetail();
        $this->functions = new LGlIntegrationFunctions();
        $this->closingEvent = new LPeriodClosingEvent();
    }

    public function index()
    {
        $module = ModuleInfo::GL_MODULE_ID;
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

        return view('gl.reportgenerator.index', compact('reports'));
    }

    public function reportParams($id)
    {
        $report = Report::find($id);
        $modules = $this->lookupManager->getGLModuleList();
        $currentFiscalYear = $this->lookupManager->getACurrentFinancialYear();

        $postingPeriod = $this->lookupManager->findPostingPeriod($currentFiscalYear->fiscal_year_id);
        $oldPostingPeriods = $this->calenderDetail->select('posting_period_display_name', 'calendar_detail_id')->where('posting_period_beg_date', '<', function ($q) {
            $q->select('posting_period_beg_date')->from('fas_calendar_detail')->where('posting_period_status', '=', 'O');
        })->orderBy('posting_period_beg_date', 'DESC')->get();
        $billSecs = $this->lookupManager->findLBillSec();
        $cashBillReg = LBillRegister::where('bill_sec_id', '=', LBillSecReg::bill_sec_id)->whereIn('bill_reg_name', LBillSecReg::S_REG)->get();

        $funcType = $this->functions->where("module_id", "=", LGlInteModules::FIN_ACC_GENE_LEDGER)->where("function_parent_id", "!=", null)->orderBy("function_id", "ASC")->get();
        $department = $this->lookupManager->getDeptCostCenter();
        $closingEvents = $this->closingEvent->get();
        $cashAccParams = $this->lookupManager->findCashAccParams();
        $lInsType = LInstrumentType::all();
        $lPeriodType = LPeriodType::whereIn('period_type_code', [\App\Enums\Common\LPeriodType::MONTH, \App\Enums\Common\LPeriodType::YEAR])->get();
        $users = DB::select("select distinct su.user_name, su.user_id, emp.emp_name
                                        from cpaacc.workflow_template wt
                                        join cpa_security.sec_role secr on wt.step_role_key = secr.role_key
                                        join cpa_security.sec_user_roles secur on secur.role_id = secr.role_id
                                        join cpa_security.sec_users su on su.user_id = secur.user_id
                                        join pmis.employee emp on emp.emp_id = su.emp_id
                                        order by emp.emp_name asc");
        $fiscalYear = DB::select("select cpaacc.FAS_REPORT_CONTROL.get_financial_years() from dual");
        //Write your dependency elements query here
        return view('gl.reportgenerator.report-params', compact('users','fiscalYear','oldPostingPeriods','modules','closingEvents', 'department', 'funcType', 'billSecs', 'cashBillReg','cashAccParams','lInsType','postingPeriod','lPeriodType','report'))->render();
    }
}
