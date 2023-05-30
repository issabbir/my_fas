<?php
/**
 *Created by PhpStorm
 *Created at ১৯/৯/২১ ৪:১৮ PM
 */

namespace App\Entities\Common;


use Illuminate\Database\Eloquent\Model;

class LApPaymentMethod extends Model
{
    protected $table = "l_ap_payment_methods";
    protected $primaryKey = "payment_method_id";
}
