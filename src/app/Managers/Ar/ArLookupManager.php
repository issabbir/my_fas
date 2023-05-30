<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 09/12/2021
 * Time: 2:27 PM
 */

namespace App\Managers\Ar;


use App\Contracts\Ar\ArLookupContract;
use App\Entities\Ar\FasArCustomers;
use App\Entities\Ar\LArCustomerCategory;
use App\Entities\Ar\LArInvoiceStatus;
use App\Entities\Ar\LArReceiptMethods;
use App\Entities\Ar\LArTransactionType;
use App\Entities\Ar\VWAgencyInfo;
use App\Entities\Common\LInstrumentType;
use App\Enums\ApprovalStatus;
use App\Enums\YesNoFlag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArLookupManager implements ArLookupContract
{

    public function getCustomers($searchQ=null)
    {
        return FasArCustomers::where('inactive_yn', YesNoFlag::NO)->where('workflow_approval_status', ApprovalStatus::APPROVED)->get(); //Add Where Condition- Pavel-14-02-22
    }

    public function getInvoiceStatus()
    {
        return LArInvoiceStatus::all();
    }

    public function getCustomerCategory(){
        return LArCustomerCategory::where('active_yn','Y')->orderBy('customer_category_id','ASC')->get();
    }

    public function getTransactionType(){
        return LArTransactionType::where('active_yn','=','Y')->get();
    }

    public function findCustomerCategory()
    {
        return LArCustomerCategory::where('active_yn','Y')->orderBy('customer_category_id','ASC')->get();
    }

    public function getLInstrumentType()
    {
        return LInstrumentType::where('active_yn','Y')->get();
    }

    public function getShippingAgents()
    {
        return VWAgencyInfo::select('agency_name','agency_id','agency_no')->orderBy('agency_name','asc')->get();
    }

    public function getArBankAcc()
    {
        //return  DB::select("select cpaacc.fas_cm_config.get_bank_account as bank_acc from dual");
        return  DB::select('select cpaacc.fas_ar_trans.get_ar_bank_account as bank_acc from dual');
    }

    public function getLArReceiptMethods()
    {
        return LArReceiptMethods::where('active_yn','Y')->get();
    }

    /*** Add this one method -Pavel: 07-04-22 ***/
    public function getArPartySubLedger($functionId = null)
    {
        return DB::select("select cpaacc.fas_ar_config.get_gl_subsidiary_ledger(:p_function_id) from dual",["p_function_id" => $functionId]);
    }

}
