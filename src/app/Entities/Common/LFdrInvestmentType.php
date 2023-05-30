<?php


namespace App\Entities\Common;


use Illuminate\Database\Eloquent\Model;

class LFdrInvestmentType extends Model
{
    protected $table = 'l_fdr_investment_type';
    protected $primaryKey = 'investment_type_id';

    public function billSection()
    {
        return $this->hasOne(LBillSection::class,'bill_sec_id','bill_sec_id');
    }

    public function billRegister()
    {
        return $this->hasOne(LBillRegister::class,'bill_reg_id','bill_reg_id');
    }

    public function billRegisterForMaturity()
    {
        return $this->hasOne(LBillRegister::class,'bill_reg_id','bill_reg_id_fdr_maturity');
    }

    public function billRegisterForOpening()
    {
        return $this->hasOne(LBillRegister::class,'bill_reg_id','bill_reg_id_fdr_opening');
    }
}
