<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 09/12/2021
 * Time: 2:27 PM
 */

namespace App\Managers\BudgetManagement;

use App\Contracts\BudgetManagement\BudgetMgtContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class BudgetMgtManager implements BudgetMgtContract
{

    public function getInitialBudgetMasterList($calendarId)
    {
        return DB::select("select CPAACC.fas_budget.get_initial_budget_master(:p_fiscal_year_id) from dual",["p_fiscal_year_id"=>$calendarId]);
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

    public function getBudgetTableHeader($calendarDetailId)
    {
        //dd($calendarDetailId);
        return DB::selectOne("select * from table (CPAACC.fas_budget.get_budget_details_header(:p_calendar_detail_id))",["p_calendar_detail_id"=>$calendarDetailId]);
    }
}
