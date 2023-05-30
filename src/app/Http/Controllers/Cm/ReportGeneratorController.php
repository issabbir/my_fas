<?php
/**
 *Created by PhpStorm
 *Created at ৩/৬/২১ ১২:১৭ PM
 */

namespace App\Http\Controllers\Cm;


use App\Contracts\Ap\ApLookupContract;
use App\Entities\Common\LGlIntegrationFunctions;
use App\Entities\Gl\CalendarDetail;
use App\Entities\Gl\LPeriodClosingEvent;
use App\Entities\Security\Report;
use App\Enums\Cm\CmFunType;
use App\Enums\Common\LGlInteModules;
use App\Enums\ModuleInfo;
use App\Http\Controllers\Controller;
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
    protected $apLookupManager;

    public function __construct(LookupManager $lookupManager, ApLookupContract $apLookupManager)
    {
        $this->lookupManager = $lookupManager;
        $this->calenderDetail = new CalendarDetail();
        $this->functions = new LGlIntegrationFunctions();
        $this->closingEvent = new LPeriodClosingEvent();
        $this->apLookupManager = $apLookupManager;
    }

    public function index(Request $request)
    {
        $module = ModuleInfo::CM_MODULE_ID;
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

        return view('cm.reportgenerator.index', compact('reports'));
    }

    /*** BLOCK PREVIOUS CODE AS PER CR--3456 ***/
    /*public function reportParams($id)
    {
        $report = Report::find($id);
        $subsidiary_type = $this->lookupManager->findPartySubLedger(LGlInteModules::CASH_MANAGEMENT);
        $vendors = $this->apLookupManager->getVendors();

        $fiscalYear = $this->lookupManager->getACurrentFinancialYear();
        //$fiscalYear = DB::select("select cpaacc.FAS_REPORT_CONTROL.get_financial_years() from dual");
        $postingPeriod = $this->lookupManager->findPostingPeriod($fiscalYear->fiscal_year_id);
        /*$oldPostingPeriods = $this->calenderDetail->select('posting_period_display_name', 'calendar_detail_id')->where('posting_period_beg_date', '<', function ($q) {
            $q->select('posting_period_beg_date')->from('fas_calendar_detail')->where('posting_period_status', '=', 'O');
        })->orderBy('posting_period_beg_date', 'DESC')->get();*/
       /* $billSecs = $this->lookupManager->getBillSections(CmFunType::CM_CLEARING_CHQ_RECONCILIATION);
        $funcType = $this->functions->where("module_id", "=", LGlInteModules::FIN_ACC_GENE_LEDGER)->where("function_parent_id", "!=", null)->where('active_yn','=','Y')->orderBy("function_id", "ASC")->get();
        $cmFuncType = $this->functions->where("module_id", "=", LGlInteModules::CASH_MANAGEMENT)->where("function_parent_id", "=", null)->where('active_yn','=','Y')->orderBy("function_id", "ASC")->get();

        $department = $this->lookupManager->getDeptCostCenter();
        $closingEvents = $this->closingEvent->get();

        //Write your dependency elements query here
        return view('cm.reportgenerator.report-params', compact('cmFuncType', 'vendors', 'subsidiary_type', 'closingEvents', 'department', 'funcType', 'billSecs', 'report', 'postingPeriod'))->render();
    }*/

    public function reportParams($id)
    {
        $report = Report::find($id);

        $transactionTypes = $this->lookupManager->getMaturityTransTypes();
        $cmFuncType = $this->functions->where("module_id", "=", LGlInteModules::CASH_MANAGEMENT)->where("function_parent_id", "!=", null)->orderBy("function_id", "ASC")->get();

        $fiscalYear = DB::select("select cpaacc.FAS_REPORT_CONTROL.get_financial_years() from dual");
        $provFiscalYear = DB::select("select cpaacc.FAS_REPORT_CONTROL.get_cm_provision_fiscal_year() from dual");
        $matFiscalYear = DB::select("select cpaacc.FAS_REPORT_CONTROL.get_cm_maturity_fiscal_year() from dual");

        //Write your dependency elements query here
        return view('cm.reportgenerator.report-params', compact( 'fiscalYear','provFiscalYear','matFiscalYear','transactionTypes','cmFuncType','report'))->render();
    }
}
