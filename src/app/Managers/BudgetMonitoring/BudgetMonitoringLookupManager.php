<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 09/12/2021
 * Time: 2:27 PM
 */

namespace App\Managers\BudgetMonitoring;


use App\Contracts\BudgetMonitoring\BudgetMonitoringLookupContract;

//use Illuminate\Support\Facades\Auth;
use App\Entities\Ap\FasApVendors;
use App\Entities\Common\LApVendorCategory;
use App\Entities\Common\LApVendorType;
use App\Entities\Common\LTenderType;
use App\Enums\ApprovalStatus;
use App\Enums\YesNoFlag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BudgetMonitoringLookupManager implements BudgetMonitoringLookupContract
{
    public function getCurrentFinancialYear()
    {
        return DB::select("select CPAACC.FAS_CONFIG.get_current_financial_year from dual");
    }

    public function getACurrentFinancialYear()
    {
        return DB::selectOne("select CPAACC.FAS_CONFIG.get_current_financial_year from dual");
    }

    public function getCurrentPostingPeriod($calendarId)
    {
        return DB::select("select CPAACC.FAS_CONFIG.get_current_posting_period(:p_calendar_id) from dual", ["p_calendar_id" => $calendarId]);
    }

    public function getDeptCostCenter($calendarId = null)
    {
        return DB::select("select CPAACC.fas_budget.get_dept_cost_centers from dual");
    }

    public function getBillSections($funcId)
    {
//        return DB::select("select CPAACC.FAS_CONFIG.get_bill_section(:p_function_id) from dual", ["p_function_id" => $funcId]);
        return DB::select("select CPAACC.FAS_CONFIG.get_bill_section(:p_function_id,:p_user_id) from dual", ["p_function_id" => $funcId,'p_user_id'=>Auth::user()->user_id]);
    }

    public function getBillRegistersOnSection($secId,$searchTerm)
    {
        $filteredSearchTerm = strtolower('%'.trim($searchTerm).'%');
        return DB::select("select CPAACC.FAS_CONFIG.get_bill_register(:p_bill_sec_id,:p_bill_reg_name) from dual",["p_bill_sec_id" => $secId, "p_bill_reg_name" => $filteredSearchTerm]);
    }

    public function getTenderTypes()
    {
        return LTenderType::orderBy('tender_type_name')->get();
    }

    public function getVendorTypes()
    {
        return LApVendorType::where('active_yn', '=', 'Y')->get();
    }

    public function getVendorCategory()
    {
        return LApVendorCategory::where('active_yn', 'Y')->get();
    }

    public function getVendors($searchQ = null)
    {
        return FasApVendors::where('inactive_yn', YesNoFlag::NO)->where('workflow_approval_status', ApprovalStatus::APPROVED)->get(); // Add Where Condition- Pavel-15-02-22
    }
}
