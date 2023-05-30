<?php
/**
 *Created by PhpStorm
 *Created at ১২/৯/২১ ৪:৩৮ PM
 */

namespace App\Entities\Common;


use Illuminate\Database\Eloquent\Model;

class FasCmBankInfo extends Model
{
    protected $table = "fas_cm_bank_info";
    protected $primaryKey = "bank_code";
}
