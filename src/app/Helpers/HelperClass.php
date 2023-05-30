<?php
//app/Helpers/HelperClass.php
namespace App\Helpers;

use App\Entities\Admin\LGeoDistrict;
use App\Entities\Admin\LGeoThana;
use App\Entities\Common\LFdrInvestmentUserMap;
use App\Entities\Security\Menu;
use App\Entities\WorkFlowMapping;
use App\Entities\WorkFlowTemplate;
use App\Enums\ModuleInfo;
use App\Enums\RolePermissionsKey;
use App\Enums\WorkFlowMaster;
use App\Enums\WorkFlowRoleKey;
use App\Managers\Authorization\AuthorizationManager;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class HelperClass
{

    public $id;
    public $links;
    /**
     * @var WorkFlowMapping
     */
    private $workFlowMapping;

    public function __construct()
    {
        $this->workFlowMapping = new WorkFlowMapping();
    }

    public static function breadCrumbs($routeName)
    {
        if (in_array($routeName, ['supplier.supplier-address-get', 'supplier.supplier-bank-info-get', 'supplier.supplier-attachments-get'])) {
            return [
                ['submenu_name' => 'Supplier', 'action_name' => ''],
                ['submenu_name' => ' Register', 'action_name' => '']
            ];
        } else if (in_array($routeName, ['invoice.invoice-edit'])) {
            return [
                ['submenu_name' => 'Invoice', 'action_name' => ''],
                ['submenu_name' => ' Entry', 'action_name' => '']
            ];
        } else if (in_array($routeName, ['invoice.invoice-payment-view'])) {
            return [
                ['submenu_name' => 'Payment', 'action_name' => ''],
                ['submenu_name' => ' Invoice', 'action_name' => '']
            ];
        } else {
            $breadMenus = [];

            try {
                $authorizationManager = new AuthorizationManager();
                $getRouteMenuId = $authorizationManager->findSubMenuId($routeName);
                if ($getRouteMenuId && !empty($getRouteMenuId)) {
                    $breadMenus[] = $bm = $authorizationManager->findParentMenu($getRouteMenuId);
                    if ($bm && isset($bm['parent_submenu_id']) && !empty($bm['parent_submenu_id'])) {
                        $breadMenus[] = $authorizationManager->findParentMenu($bm['parent_submenu_id']);
                    }
                }
            } catch (\Exception $e) {
                return false;
            }

            return is_array($breadMenus) ? array_reverse($breadMenus) : false;
        }
    }

    public static function findDistrictByDivision($divisionId)
    {
        return LGeoDistrict::where('geo_division_id', $divisionId)->get();
    }

    public static function findDivisionByThana($districtId)
    {
        return LGeoThana::where('geo_district_id', $districtId)->get();
    }

    public static function isNewspaper($typeId)
    {
        return ((AppointmentType::NEWSPAPER_ADVERTISEMENT == $typeId) || ($typeId == null));
    }

    public static function isSupplierAgency($typeId)
    {
        return (AppointmentType::SUPPLIER_AGENCY == $typeId);
    }

    public const REQUIRED = 'required';

    public static function getRequiredForNewsPaper($typeId)
    {
        if (static::isNewspaper($typeId))
            return static::REQUIRED;

        return '';
    }

    public static function getRequiredForSupplierAgency($typeId)
    {
        if (static::isSupplierAgency($typeId))
            return static::REQUIRED;

        return '';
    }


    public static function dateTimeConvert($dateTime = null)
    {
        return isset($dateTime) ? date('d-m-Y H:i:s', strtotime($dateTime)) : date('d-m-Y H:i:s');
    }

    public static function previousMonth($dateTime = null)
    {
        if ($dateTime) {
            $dt = Carbon::createFromFormat('Y-m-d H:i:s', $dateTime);
            return $dt->subMonth()->format('d-m-Y');
        } else {
            return $dateTime;
        }
    }

    public static function dateConvert($dateTime = null)
    {
        if (isset($dateTime)) {
            return date('d-m-Y', strtotime($dateTime));
        } else {
            return $dateTime;
        }
    }

    public static function dateFormatForDB($date = null)
    {
        if (isset($date)) {
            return date("Y-m-d", strtotime($date));
        } else {
            return $date;
        }
    }

    public static function getCurrentDate()
    {
        return date("d-m-Y");
    }

    public static function validate_file_extension($givenExtension)
    {
        $allowedExtensions = ['docx', 'xlsx', 'jpg', 'jpeg', 'pdf'];

        if (!in_array($givenExtension, $allowedExtensions)) {
            return false;
        } else {
            return true;
        }
    }

    public static function workflow($refTable, $refColumn, $refId, $wkFlowMasterId)
    {

        $approval_status = DB::selectOne(DB::raw("SELECT workflow_approval_status FROM " . $refTable . " WHERE " . $refColumn . " = :col_id"), ['col_id' => $refId]);

        $workflows = WorkFlowTemplate::with(['template_wise_map' => function ($query) use ($refTable, $refId) {
            $query->where('reference_table', $refTable);
            $query->where('reference_id', $refId);
        }])->where('workflow_master_id', $wkFlowMasterId)
            ->get();

        return view('fas-common.workflow', compact('workflows', 'approval_status'))->render();
    }

    /*public function hasRoleActionPermission($workFlowMasterId,$referenceId,$userId)
    {
        $approval = $this->workFlowMapping->where(["workflow_master_id" => $workFlowMasterId, "user_id" => null, "reference_id" => $referenceId])
            ->with(["template.sec_role.user_roles" => function ($q) use($userId) {
                $q->where("user_id", $userId);
            }])
            ->first();
        if (($approval != null) && ($approval->template != null)) {
            if (!$approval->template->sec_role->user_roles->isEmpty()) {
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }*/

    /**
     ** Create Manjurul Islam Pavel
    **/
    public static function findRolePermission($moduleId, $roleKey, $permissionKey)
    {
        $userId = auth()->id();
        $rolePermissionUser = '';

        if ($moduleId == ModuleInfo::GL_MODULE_ID) {
            $roleKeyParam = [WorkFlowRoleKey::GL_JOURNAL_VOUCHER_MAKE, WorkFlowRoleKey::GL_PAYMENT_VOUCHER_MAKE, WorkFlowRoleKey::GL_RECEIVE_VOUCHER_MAKE, WorkFlowRoleKey::GL_TRANSFER_VOUCHER_MAKE];
            $perKeyParam = [RolePermissionsKey::CAN_EDIT_GL_JOURNAL_VOUCHER_MAKE, RolePermissionsKey::CAN_EDIT_GL_PAYMENT_VOUCHER_MAKE, RolePermissionsKey::CAN_EDIT_GL_RECEIVE_VOUCHER_MAKE, RolePermissionsKey::CAN_EDIT_GL_TRANSFER_VOUCHER_MAKE];

            $inClauseRoleKeyVal = "'" . implode("', '", $roleKeyParam) . "'";
            $inClausePerKeyVal = "'" . implode("', '", $perKeyParam) . "'";

            $includeQuery = <<<INCLUDED_CLAUSE
    AND D.ROLE_KEY IN ( $inClauseRoleKeyVal ) AND A.PERMISSION_KEY IN ( $inClausePerKeyVal )
INCLUDED_CLAUSE;
        } else {
            $includeQuery = <<<INCLUDED_CLAUSE
    AND D.ROLE_KEY = :p_role_key AND A.PERMISSION_KEY = :p_permission_key
INCLUDED_CLAUSE;
        }

        $query = <<<QUERY
SELECT A.*,
       B.ROLE_ID,
       C.USER_ID,
       D.ROLE_NAME
  FROM CPA_SECURITY.SEC_PERMISSIONS       A,
       CPA_SECURITY.SEC_ROLE_PERMISSIONS  B,
       CPA_SECURITY.SEC_USER_ROLES        C,
       CPA_SECURITY.SEC_ROLE              D
 WHERE     A.MODULE_ID = :p_module_id
       AND A.PERMISSION_ID = B.PERMISSION_ID
       AND B.ROLE_ID = C.ROLE_ID
       AND D.ROLE_ID = C.ROLE_ID
       AND D.ROLE_ID = B.ROLE_ID
       --AND D.ROLE_KEY = :p_role_key
       --AND A.PERMISSION_KEY = :p_permission_key
        $includeQuery
       AND C.USER_ID = :p_user_id
QUERY;

        if ($moduleId == ModuleInfo::GL_MODULE_ID) {
            $rolePermissionUser = DB::select($query, ['p_module_id' => $moduleId, 'p_user_id' => $userId]);
        } else {
            $rolePermissionUser = DB::selectOne($query, ['p_module_id' => $moduleId, 'p_role_key' => $roleKey, 'p_permission_key' => $permissionKey, 'p_user_id' => $userId,]);
        }

        //dd($userId,$moduleId, $roleKey, $permissionKey,$rolePermissionUser);
        return $rolePermissionUser;
    }

    /**
     ** Create Manjurul Islam Pavel
     ** Date 03-05-2023
     **/
    public static function findUserPermissionKey ($moduleId, $permissionKey)
    {

        $userId = auth()->id();

        $query = <<<QUERY
SELECT A.*,
       B.ROLE_ID,
       C.USER_ID
  FROM CPA_SECURITY.SEC_PERMISSIONS       A,
       CPA_SECURITY.SEC_ROLE_PERMISSIONS  B,
       CPA_SECURITY.SEC_USER_ROLES        C
 WHERE     A.MODULE_ID = :p_module_id
       AND A.PERMISSION_ID = B.PERMISSION_ID
       AND B.ROLE_ID = C.ROLE_ID
       AND A.PERMISSION_KEY = :p_permission_key
       AND C.USER_ID = :p_user_id
QUERY;


            $permissionKeyUser = DB::selectOne($query, ['p_module_id' => $moduleId, 'p_permission_key' => $permissionKey, 'p_user_id' => $userId,]);

        return $permissionKeyUser;
    }

    /**
     * Following function returns an amount with comma separated
     * Sujon Chondro Shil
     */
    public static function getCommaSeparatedValue($amount, $americanBritish = 'B')
    {

        $array = explode('.', $amount);
        $decimal = $array[0];
        $fraction = key_exists(1, $array) ? $array[1] : '00';
        /**Converting decimal values to array.**/
        $decimalArray = str_split($decimal);
        $length = count($decimalArray);

        /**When decimal is not 0 nor null and total digit length greater than 3
         * then comma applied, either given number returned.
         **/
        if (($decimal != 0 || !empty($decimal)) && $length > 3) {
            /**
             * Total steps needed to add the comma.
             **/
            $steps = floor($length / 2);
            $position = $length;
            for ($i = 0; $i < $steps; $i++) {
                if (strtoupper($americanBritish) == 'B') {
                    if ($i == 0) {
                        /**Three step comma.**/
                        $position -= 3;
                    } else {
                        /**Two step comma.**/
                        $position -= 2;
                    }
                } else {
                    $position -= 3;
                }

                if ($position > 0) {
                    /**Adding comma to the length position.**/
                    array_splice($decimalArray, $position, 0, ',');
                }
            }
            /**To concat all the array elements.**/
            return implode('', $decimalArray) . '.' . $fraction;
        } else {
            return $amount;
        }

    }

    /**
     * Following function returns an amount without comma
     * Sujon Chondro Shil
     */
    public static function removeCommaFromValue($amount)
    {
        $array = explode('.', $amount);
        $decimal = explode(',', $array[0]);
        $fraction = isset($array[1]) ? '.' . $array[1] : '';

        return implode('', $decimal) . $fraction;
    }

    public static function checkRoleStatus($roleKey)
    {
        return DB::selectOne('SELECT
                                            sur.role_id
                                    FROM
                                        cpa_security.sec_user_roles sur
                                    JOIN cpa_security.sec_role sr ON sur.role_id = sr.role_id
                                    WHERE sur.user_id = :p_user AND sr.role_key = :p_role_key AND sur.active_yn = \'Y\'',
            ['p_user' => Auth::id(), 'p_role_key' => $roleKey]);
    }

    public static function getUserCurrentInvestType($userId)
    {
        return(LFdrInvestmentUserMap::select('investment_type_id')->where('investment_user_id', '=', $userId)->first())->investment_type_id ?? null;
    }
}
