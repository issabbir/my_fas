<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 09/12/2021
 * Time: 2:27 PM
 */

namespace App\Managers\BudgetManagement;


use App\Contracts\BudgetManagement\BudgetMgtLookupContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BudgetMgtLookupManager implements BudgetMgtLookupContract
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
        return DB::select("select CPAACC.FAS_CONFIG.get_current_posting_period(:p_calendar_id) from dual",["p_calendar_id"=>$calendarId]);
    }
    public function getDeptCostCenter($calendarId=null)
    {
        //return DB::select("select CPAACC.fas_budget.get_initial_dept_cost_centers(:p_fiscal_calendar_id) from dual",["p_fiscal_calendar_id"=>(int)$calendarId]);
        return DB::select("select CPAACC.fas_budget.get_dept_cost_centers from dual");
    }

    public function getBudgetDetailHeads($transPeriodId)
    {
        return DB::select("SELECT * FROM TABLE (FAS_BUDGET.get_budget_details_header (:p_trans_period_id))");
    }
}
