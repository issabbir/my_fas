<?php
/**
 *Created by PhpStorm
 *Created at ১৯/৯/২১ ৪:১৪ PM
 */

namespace App\Entities\Common;


use Illuminate\Database\Eloquent\Model;

class LApPaymentTerm extends Model
{
    protected $table = "l_ap_payment_terms";
    protected $primaryKey = "payment_term_id";
}
