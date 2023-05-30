<?php
/**
 *Created by PhpStorm
 *Created at ২৪/১১/২১ ৪:০৫ PM
 */

namespace App\Entities\Common;


use Illuminate\Database\Eloquent\Model;

class LCostCenterDept extends Model
{
    protected $table = "l_cost_center_dept";
    protected $primaryKey = 'cost_center_dept_id';
}
