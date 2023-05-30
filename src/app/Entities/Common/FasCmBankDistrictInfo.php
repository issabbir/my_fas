<?php
/**
 *Created by PhpStorm
 *Created at ১২/৯/২১ ৪:৪০ PM
 */

namespace App\Entities\Common;


use Illuminate\Database\Eloquent\Model;

class FasCmBankDistrictInfo extends Model
{
    protected $table = "fas_cm_bank_district";
    protected $primaryKey = "district_code";
}
