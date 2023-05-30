<?php
/**
 *Created by PhpStorm
 *Created at ২০/১১/২১ ১০:১৭ AM
 */

namespace App\Entities\BudgetManagement;


use Illuminate\Database\Eloquent\Model;

class FasBudgetMgtMaster extends Model
{
    //public $table = "cpaacc.fas_budget_mgt_master";
    public $table = "cpaacc.fas_budget_est_master";
    protected $primaryKey = "budget_master_id";

    public function budgetDetail()
    {
        return $this->hasMany(FasBudgetMgtDetail::class,"budget_master_id","budget_master_id");
    }

    public function attachments()
    {
        return $this->hasMany(FasBudgetMgtDocs::class,"budget_master_id","budget_master_id");
    }
}
