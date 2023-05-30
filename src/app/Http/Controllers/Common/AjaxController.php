<?php


namespace App\Http\Controllers\Common;

use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Entities\Budget\BudgetHeadLines;
use App\Entities\BudgetManagement\FasBudgetHead;
use App\Enums\YesNoFlag;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Managers\Pmis\Employee\EmployeeManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{

    /** @var EmployeeManager */
    private $employeeManager;


    public function __construct(EmployeeContract $employeeManager)
    {
        $this->employeeManager = $employeeManager;
    }

    public function employees(Request $request)
    {
        $searchTerm = $request->get('term');
        $employees = $this->employeeManager->findEmployeeCodesBy($searchTerm);

        return $employees;
    }

    public function employee(Request $request, $empId)
    {
        return $this->employeeManager->findEmployeeInformation($empId);
    }


    /*public function budgetHeadLineDetails(Request $request, $budgetHeadLineId)
    {
        $budgetHeadLineInfo = '';
        $budgetHeadLineInfo = BudgetHeadLines::where('budget_head_line_id', $budgetHeadLineId)->first();
        return $budgetHeadLineInfo;
    }*/

    public function budgetHeadDetails(Request $request, $budgetHeadId)
    {
        $budgetHeadInfo = '';
        $budgetHeadInfo = FasBudgetHead::where('budget_head_id', $budgetHeadId)->first();
        //return $budgetHeadInfo;
        return response()->json($budgetHeadInfo);
    }


}
