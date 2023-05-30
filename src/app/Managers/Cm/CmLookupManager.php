<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 09/12/2021
 * Time: 2:27 PM
 */

namespace App\Managers\Cm;


use App\Contracts\Cm\CmLookupContract;
use App\Entities\Common\LGlIntegrationFunctions;
use App\Enums\Common\LGlInteFun;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CmLookupManager implements CmLookupContract
{

    public function getBankAcc()
    {
        return  DB::select("select cpaacc.fas_cm_config.get_bank_account as bank_acc from dual");
    }

    //CM look up manager

    public function getFunctionType()
    {
        return LGlIntegrationFunctions::select('function_id','function_name')->where('function_parent_id','=',LGlInteFun::CM_CLEARING_RECON_PROCESS)->get();
    }

}
