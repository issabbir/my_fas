<?php


namespace App\Entities\BudgetManagement;


use App\Entities\Common\LBudgetCategory;
use App\Entities\Common\LBudgetSubCategory;
use App\Entities\Common\LBudgetType;
use App\Entities\Common\LCostCenterClusterMaster;
use App\Entities\Common\LCostCenterDept;
use App\Entities\Gl\GlCoa;
use App\Entities\Pmis\Department;
use Illuminate\Database\Eloquent\Model;

class FasBudgetHead extends Model
{
    protected $table = "fas_budget_head";
    protected $primaryKey = "budget_head_id";

    public function budget_type()
    {
        return $this->belongsTo(LBudgetType::class,'budget_type_id','budget_type_id');
    }

    public function gl_coa()
    {
        return $this->belongsTo(GlCoa::class,'gl_acc_id','gl_acc_id');
    }

    public function head_parent_info()
    {
        return $this->belongsTo(FasBudgetHead::class,'budget_parent_id');
    }

    public function budget_category()
    {
        return $this->belongsTo(LBudgetCategory::class,'budget_category_id','budget_category_id');
    }

    public function budget_sub_category()
    {
        return $this->belongsTo(LBudgetSubCategory::class,'budget_sub_category_id','budget_sub_category_id');
    }

    public function department()
    {
        return $this->belongsTo(LCostCenterDept::class, 'cost_center_dept_id', 'cost_center_dept_id');
    }

    public function department_cluster()
    {
        return $this->belongsTo(LCostCenterClusterMaster::class, 'cost_center_cluster_id', 'cost_center_cluster_id');
    }

    public function budget_booking_dept()
    {
        return $this->belongsTo(LCostCenterDept::class, 'budget_booking_dept_id', 'cost_center_dept_id');
    }
}
