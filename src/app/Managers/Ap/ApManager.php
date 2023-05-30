<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 09/12/2021
 * Time: 2:27 PM
 */
namespace App\Managers\Ap;
use App\Contracts\Ap\ApContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class ApManager implements ApContract
{

    // Method ap manager

    /*** Add this one method start -Pavel: 24-03-22 ***/
    public function findRoleWiseUser($workflowMasterId, $stepRoleKey, $userID)
    {
        $roleWiseUser = '';
        $query = <<<QUERY
SELECT WT.WORKFLOW_TEMPLATE_ID,
       WT.WORKFLOW_MASTER_ID,
       WT.STEP_ROLE_KEY,
       SEC.USER_ID     LOGIN_USER_ID,
       SEC.ROLE_ID     LOGIN_ROLE_ID
  FROM CPAACC.WORKFLOW_TEMPLATE     WT,
       CPA_SECURITY.SEC_USER_ROLES  SEC,
       CPA_SECURITY.SEC_ROLE        SR,
       CPA_SECURITY.SEC_USERS       SU
 WHERE     WT.WORKFLOW_MASTER_ID = :p_workflow_master_id   --'5'
       AND WT.STEP_ROLE_KEY = :p_step_role_key  --'AP_INVOICE_BILL_ENTRY_MAKE'
       AND SR.ROLE_ID = SEC.ROLE_ID
       AND SR.ROLE_KEY = WT.STEP_ROLE_KEY
       ---PARAMETER PART----
       AND SU.USER_ID = SEC.USER_ID
       AND SU.USER_ID =  :p_user_id  --'2002260190/2106302944'
QUERY;

        $roleWiseUser = DB::selectOne($query, ['p_workflow_master_id' => $workflowMasterId, 'p_step_role_key' => $stepRoleKey, 'p_user_id' => $userID]);
        return $roleWiseUser;
    }
    /*** Add this two method end -Pavel: 24-03-22 ***/
}
