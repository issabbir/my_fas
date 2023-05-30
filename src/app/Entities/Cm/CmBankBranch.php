<?php


namespace App\Entities\Cm;

use Illuminate\Database\Eloquent\Model;

class CmBankBranch extends Model
{
    protected $table = 'fas_cm_bank_branch';
    protected $primaryKey = 'branch_code';
    protected $keyType = 'string';

    public function cm_bank_info()
    {
        return $this->belongsTo(CmBankInfo::class,"bank_code","bank_code");
    }

    public function cm_bank_district()
    {
        return $this->belongsTo(CmBankDistrict::class,"district_code","district_code");
    }
}
