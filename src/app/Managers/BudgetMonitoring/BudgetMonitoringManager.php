<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 09/12/2021
 * Time: 2:27 PM
 */

namespace App\Managers\BudgetMonitoring;

use App\Contracts\BudgetMonitoring\BudgetMonitoringContract;
use Illuminate\Support\Facades\DB;

//use Illuminate\Support\Facades\Auth;


class BudgetMonitoringManager implements BudgetMonitoringContract
{

    public function getInitialBudgetMasterList($calendarId)
    {
        return DB::select("select CPAACC.fas_budget.get_initial_budget_master(:p_fiscal_year_id) from dual", ["p_fiscal_year_id" => $calendarId]);
    }

    public function findWorkflowWiseDpt($workflowMasterId, $stepRoleKey, $userID)
    {
        $workflowWiseDpt = '';
        $query = <<<QUERY
SELECT EMP.DPT_DEPARTMENT_ID,
       WT.WORKFLOW_TEMPLATE_ID,
       WT.WORKFLOW_MASTER_ID,
       WT.STEP_ROLE_KEY,
       SEC.USER_ID     LOGIN_USER_ID,
       SEC.ROLE_ID     LOGIN_ROLE_ID
  FROM CPAACC.WORKFLOW_TEMPLATE     WT,
       CPA_SECURITY.SEC_USER_ROLES  SEC,
       CPA_SECURITY.SEC_ROLE        SR,
       CPA_SECURITY.SEC_USERS       SU,
       PMIS.EMPLOYEE                EMP
 WHERE     WT.WORKFLOW_MASTER_ID = :p_workflow_master_id   --'12'
       AND WT.STEP_ROLE_KEY = :p_step_role_key  --'BUDGET_MGT_DEPARTMENT_REVIEW'
       AND SR.ROLE_ID = SEC.ROLE_ID
       AND SR.ROLE_KEY = WT.STEP_ROLE_KEY
       ---PARAMETER PART----
       AND EMP.EMP_ID = SU.EMP_ID
       AND SU.USER_ID = SEC.USER_ID
       AND SU.USER_ID = :p_user_id  --'2106301814'
QUERY;

        $workflowWiseDpt = DB::selectOne($query, ['p_workflow_master_id' => $workflowMasterId, 'p_step_role_key' => $stepRoleKey, 'p_user_id' => $userID]);
        return $workflowWiseDpt;
    }

    public function getBudgetHeadListOfDept($calendar_id, $dept_id)
    {
        return DB::select("select CPAACC.fas_budget.get_budget_booking_head_list(:p_fiscal_year_id, :p_cost_center_dept_id) from dual", ["p_fiscal_year_id" => $calendar_id, "p_cost_center_dept_id" => $dept_id]);
    }

    public function getBudgetHeadDetailInfo($calendar_id, $dept_id, $budget_id)
    {
        return DB::selectOne("select CPAACC.fas_budget.get_budget_booking_head_info(:p_fiscal_year_id, :p_cost_center_dept_id, :p_budget_head_id) from dual", ["p_fiscal_year_id" => $calendar_id, "p_cost_center_dept_id" => $dept_id, "p_budget_head_id" => $budget_id]);
    }

    public function getCurrentPostingPeriod($calendarId)
    {
        return DB::select("select CPAACC.FAS_CONFIG.get_current_posting_period(:p_calendar_id) from dual", ["p_calendar_id" => $calendarId]);
    }

    public function budgetHeadListForReport($calendar, $department = null, $q)
    {
        /*return DB::select("
        SELECT DISTINCT a.budget_head_id
               ,a.budget_head_name AS budget_head_name
        FROM fas_budget_head a
              ,fas_budget_booking_master b
        WHERE a.budget_head_id = b.budget_head_id
        AND b.fiscal_year_id = :p_year
        AND b.cost_center_dept_id = nvl(:p_dept, b.cost_center_dept_id)
        AND upper(a.budget_head_name) LIKE nvl('%" . strtoupper($q) . "%', upper(a.budget_head_name))
        ",["p_year"=>$calendar, "p_dept"=>$department]);*/

        /**Getting data with code**/
        return DB::select("select cpaacc.FAS_REPORT_CONTROL.get_budget_head_names(:search) from dual",["search"=>$q]);
    }
    /*public function budgetHeadListForReport($calendar, $department, $q)
    {
        return  DB::select("
        SELECT a.budget_head_id
              ,a.budget_head_name         AS budget_head_name
              ,e.budget_sub_category_name AS sub_category_name
              ,d.budget_category_name     AS category_name
              ,c.budget_type_name         AS budget_type_name
              ,b.budget_balance_amt
        FROM   fas_budget_head           a
              ,fas_budget_booking_master b
              ,l_budget_type             c
              ,l_budget_category         d
              ,l_budget_sub_category     e
        WHERE  a.budget_head_id = b.budget_head_id
        AND    a.budget_type_id = c.budget_type_id
        AND    a.budget_category_id = d.budget_category_id
        AND    a.budget_sub_category_id = e.budget_sub_category_id
        AND    b.fiscal_year_id = :p_year
        AND    b.cost_center_dept_id = :p_dept
        AND    (UPPER (a.budget_head_name) like NVL('%".strtoupper($q)."%',UPPER(a.budget_head_name)))
        ",["p_year"=>$calendar, "p_dept"=>$department]);
    }*/
}
