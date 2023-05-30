<?php


namespace App\Entities\Cm;

use Illuminate\Database\Eloquent\Model;

class CmBankInfo extends Model
{
    protected $table = 'fas_cm_bank_info';
    protected $primaryKey = 'bank_code';
    protected $keyType = 'string';

}
