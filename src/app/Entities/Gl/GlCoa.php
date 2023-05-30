<?php


namespace App\Entities\Gl;


use App\Entities\Budget\BudgetHeadLines;
use App\Entities\BudgetManagement\FasBudgetHead;
use App\Entities\Common\LCostCenterDept;
use App\Entities\Common\LCurrency;
use Illuminate\Database\Eloquent\Model;

class GlCoa extends Model
{
    protected $table = 'fas_gl_coa';
    protected $primaryKey = 'gl_acc_id';

    public function acc_type()
    {
        return $this->belongsTo(GlCoaParams::class, 'gl_type_id','gl_type_id');
    }

    public function l_curr()
    {
        return $this->belongsTo(LCurrency::class, 'currency_code','currency_code');
    }

    public function budget_head_line()
    {
        return $this->belongsTo(BudgetHeadLines::class, 'budget_head_line_id','budget_head_line_id');
    }

    public function budget_head()
    {
        /**
         * COA EDIT (problem: not shown parent name, budget  name). REF# email
         * budget_head_line_id to budget_head_id
         * Logic modified:04-04-2022
         * **/
        return $this->belongsTo(FasBudgetHead::class, 'budget_head_id','budget_head_id');
    }

    public function coa_parent_info()
    {
        return $this->belongsTo(GlCoa::class, 'gl_parent_id','gl_acc_id');
    }

    public function gl_tran_dtl()
    {
        return $this->belongsTo(GlTransDetail::class, 'gl_acc_id','gl_acc_id');
    }

    public function cost_center_dep()
    {
        return $this->belongsTo(LCostCenterDept::class, 'cost_center_dept_id','cost_center_dept_id');
    }
}
