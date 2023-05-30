<?php
/**
 *Created by PhpStorm
 *Created at ১৩/৯/২১ ৯:৪১ AM
 */

namespace App\Entities\Ap;


use App\Entities\Cm\CmBankBranch;
use App\Entities\Common\LApVendorCategory;
use App\Entities\Common\LApVendorType;
use Illuminate\Database\Eloquent\Model;

class FasApVendors extends Model
{
    protected $table = "fas_ap_vendors";
    protected $primaryKey = "vendor_id";

    public function vendor_category()
    {
        return $this->belongsTo(LApVendorCategory::class,'vendor_category_id','vendor_category_id');
    }

    public function vendor_type()
    {
        return $this->belongsTo(LApVendorType::class, 'vendor_type_id','vendor_type_id');
    }

    public function vendor_address()
    {
        return $this->hasOne(FasApVendorAddress::class,'vendor_id','vendor_id');
    }

    public function branch_code()
    {
        return $this->belongsTo(CmBankBranch::class,'bank_branch_code','branch_code');
    }
}
