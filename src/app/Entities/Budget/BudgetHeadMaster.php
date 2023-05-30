<?php


namespace App\Entities\Budget;


use Illuminate\Database\Eloquent\Model;

class BudgetHeadMaster extends Model
{
    protected $table = 'budget.budget_head_master';
    protected $primaryKey = 'budget_group_id';
}
