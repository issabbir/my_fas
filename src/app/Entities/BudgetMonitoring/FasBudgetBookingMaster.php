<?php
/**
 *Created by PhpStorm
 *Created at ২৪/১১/২১ ৪:৪৮ PM
 */

namespace App\Entities\BudgetMonitoring;


use App\Entities\BudgetManagement\FasBudgetHead;
use Illuminate\Database\Eloquent\Model;

class FasBudgetBookingMaster extends Model
{
    protected $table = "cpaacc.fas_budget_booking_master";

    public function budget_head()
    {
        return $this->hasOne(FasBudgetHead::class, 'budget_head_id','budget_head_id');
    }
}
