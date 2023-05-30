<?php

namespace App\Helpers;

use App\Entities\Security\Menu;
use App\Enums\ModuleInfo;
use App\Enums\RolePermissionsKey;
use App\Enums\WorkFlowRoleKey;
use App\Managers\Authorization\AuthorizationManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class GlClass
{

    public $id;
    public $links;

    /**
     * @return mixed
     */
    public static function menuSetup()
    {
        if (Auth::user()->hasGrantAll()) {
            $moduleId = ModuleInfo::GL_MODULE_ID;
            $menus = Menu::where('module_id', $moduleId)->orderBy('menu_order_no')->get();

            return $menus;
        } else {
            $allMenus = Auth::user()->getRoleMenus();
            $menus = [];

            if($allMenus) {
                foreach($allMenus as $menu) {
                    if($menu->module_id == ModuleInfo::GL_MODULE_ID) {
                        $menus[] = $menu;
                    }
                }
            }

            return $menus;
        };
    }

    public static function getActiveRouteNameWrapping($routeName)
    {
        if (in_array($routeName, ['secdbms.ims.incidence-type.edit'])) {
            return 'bank-account-setup.index';
        } else if (in_array($routeName, ['secdbms.ims.incidence-setup.edit'])) {
            return 'secdbms.ims.incidence-setup.index';
        } else if (in_array($routeName, ['secdbms.ims.incidence-setup.edit'])) {
            return 'secdbms.ims.incidence-setup.index';
        } else if (in_array($routeName, ['secdbms.ims.incidence-location.edit'])) {
            return 'secdbms.ims.incidence-location.index';
        } else if (in_array($routeName, ['secdbms.ims.incident-entry.edit'])) {
            return 'secdbms.ims.incident-entry.index';
        } else if (in_array($routeName, ['secdbms.ims.incident-assignment.edit'])) {
            return 'secdbms.ims.incident-assignment.index';
        } else if (in_array($routeName, ['secdbms.ims.incident-investigation.edit'])) {
            return 'secdbms.ims.incident-investigation.index';
        } else if (in_array($routeName, ['secdbms.ims.incident-report-submission.form'])) {
            return 'secdbms.ims.incident-report-submission.index';
        }  else if (in_array($routeName, ['secdbms.ims.incident-action-circulation.edit'])) {
            return 'secdbms.ims.incident-action-circulation.index';
        } else if (in_array($routeName, ['secdbms.ims.incident-report-other-info.form'])) {
            return 'secdbms.ims.incident-report-other-info.index';
        } else {
            return [
                [
                    'submenu_name' => $routeName,
                ]
            ];
        }
    }

    public static function activeMenus($routeName)
    {
        //$menus = [];
        try {
            $authorizationManager = new AuthorizationManager();
            $menus[] = $getRouteMenuId = $authorizationManager->findSubMenuId(self::getActiveRouteNameWrapping($routeName));

            if ($getRouteMenuId && !empty($getRouteMenuId)) {
                $bm = $authorizationManager->findParentMenu($getRouteMenuId);
                $menus[] = $bm['parent_submenu_id'];
                if ($bm && isset($bm['parent_submenu_id']) && !empty($bm['parent_submenu_id'])) {
                    $m = $authorizationManager->findParentMenu($bm['parent_submenu_id']);
                    if (!empty($m['submenu_id'])) {
                        $menus[] = $m['submenu_id'];
                    }
                }
            }
        } catch (\Exception $e) {
            $menus = [];
        }
        return is_array($menus) ? $menus : false;
    }

    public static function hasChildMenu($routeName)
    {
        $authorizationManager = new AuthorizationManager();
        $getRouteMenuId = $authorizationManager->findSubMenuId($routeName);
        return $authorizationManager->hasChildMenu($getRouteMenuId);
    }

    public static function findGlRolePermission($moduleId)
    {
        $userId = auth()->id();
        $rolePermissionUser = '';

        $roleKeyParam = [ WorkFlowRoleKey::GL_JOURNAL_VOUCHER_MAKE, WorkFlowRoleKey::GL_PAYMENT_VOUCHER_MAKE, WorkFlowRoleKey::GL_RECEIVE_VOUCHER_MAKE, WorkFlowRoleKey::GL_TRANSFER_VOUCHER_MAKE ];
        $perKeyParam = [ RolePermissionsKey::CAN_EDIT_GL_JOURNAL_VOUCHER_MAKE, RolePermissionsKey::CAN_EDIT_GL_PAYMENT_VOUCHER_MAKE, RolePermissionsKey::CAN_EDIT_GL_RECEIVE_VOUCHER_MAKE, RolePermissionsKey::CAN_EDIT_GL_TRANSFER_VOUCHER_MAKE ] ;

        $inClauseRoleKeyVal = "'".implode("', '", $roleKeyParam)."'";
        $inClausePerKeyVal = "'".implode("', '", $perKeyParam)."'";

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
       --AND D.ROLE_KEY IN (:p1)
       AND D.ROLE_KEY IN ( $inClauseRoleKeyVal )
       AND C.USER_ID = :p_user_id
       --AND A.PERMISSION_KEY IN (:p2)
       AND A.PERMISSION_KEY IN ( $inClausePerKeyVal )
QUERY;

        $rolePermissionUser = DB::select($query, ['p_module_id' => $moduleId,'p_user_id' => $userId]);
        return $rolePermissionUser;

    }

}
