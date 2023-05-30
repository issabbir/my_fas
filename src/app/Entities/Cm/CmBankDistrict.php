<?php


namespace App\Entities\Cm;

use Illuminate\Database\Eloquent\Model;

class CmBankDistrict extends Model
{
    protected $table = 'fas_cm_bank_district';
    protected $primaryKey = 'district_code';
    protected $keyType = 'string';

}
