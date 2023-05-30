<?php
/**
 *Created by PhpStorm
 *Created at ১২/৯/২১ ৪:৪৪ PM
 */

namespace App\Entities\Common;


use Illuminate\Database\Eloquent\Model;

class LBankAccountType extends Model
{
    protected $table = "l_bank_account_type";
    protected $primaryKey = "bank_account_type_id";
}
