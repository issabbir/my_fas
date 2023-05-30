<?php
/**
 *Created by PhpStorm
 *Created at ১২/৯/২১ ৪:৪২ PM
 */

namespace App\Entities\Common;


use Illuminate\Database\Eloquent\Model;

class FasCmBankBranchInfo extends Model
{
    protected $table = "fas_cm_bank_branch";
    protected $primaryKey = 'branch_code';
    protected $keyType = 'string';

    public function bank_district()
    {
        return $this->belongsTo(FasCmBankDistrictInfo::class,"district_code",'district_code');
    }
}
