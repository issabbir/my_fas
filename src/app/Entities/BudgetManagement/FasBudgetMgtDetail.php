<?php
/**
 *Created by PhpStorm
 *Created at ২১/১১/২১ ১২:৪১ PM
 */

namespace App\Entities\BudgetManagement;


use Illuminate\Database\Eloquent\Model;

class FasBudgetMgtDetail extends Model
{
    //protected $table = "cpaacc.fas_budget_mgt_detail";
    protected $table = "cpaacc.fas_budget_est_detail";
    protected $primaryKey = "budget_detail_id";
}
