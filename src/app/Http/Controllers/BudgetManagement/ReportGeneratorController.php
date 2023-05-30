<?php
/**
 *Created by PhpStorm
 *Created at ৩/৬/২১ ১২:১৭ PM
 */

namespace App\Http\Controllers\BudgetManagement;


use App\Contracts\LookupContract;
use App\Entities\Common\LCostCenterDept;
use App\Entities\Common\LGlIntegrationFunctions;
use App\Entities\Security\Report;
use App\Enums\Common\LGlInteModules;
use App\Enums\ModuleInfo;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\LookupManager;
use Illuminate\Http\Request;
use App\Traits\Security\HasPermission;

class ReportGeneratorController extends Controller
{
    use HasPermission;

    /** @var LookupManager */
    private $lookupManager;


    public function __construct(LookupContract $lookupManager)
    {
        $this->lookupManager = $lookupManager;
    }

    public function index(Request $request)
    {

        $module = ModuleInfo::BUDGET_MGT_MODULE_ID;

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

        return view('budget-management.reportgenerator.index', compact('reports'));
    }

    public function reportParams($id)
    {
        $report = Report::find($id);
        //dd($report->params);

        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        $lCostCenterDpt = $this->lookupManager->getDeptCostCenter();
        //dd($fiscalYear);

        return view('budget-management.reportgenerator.report-params', compact( 'report','fiscalYear', 'lCostCenterDpt'))->render();
    }
}
