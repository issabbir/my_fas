<?php


namespace App\Entities;


use App\Entities\Pmis\Employee\Employee;
use Illuminate\Database\Eloquent\Model;

class WorkFlowMapping extends Model
{
    protected $table = "workflow_mapping";

    public function master()
    {
       return $this->belongsTo(WorkFlowMaster::class,"workflow_master_id","workflow_master_id");
    }

    public function template()
    {
        return $this->belongsTo(WorkFlowTemplate::class,"workflow_template_id","workflow_template_id");
    }

    public function emp_info()
    {
        return $this->belongsTo(Employee::class,"emp_id","emp_id");
    }
}
