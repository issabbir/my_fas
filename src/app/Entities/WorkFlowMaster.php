<?php


namespace App\Entities;


use Illuminate\Database\Eloquent\Model;

class WorkFlowMaster extends Model
{
    protected $table = "workflow_master";
    protected $primaryKey = "workflow_master_id";

    public function mapping()
    {
        return $this->hasMany(WorkFlowMapping::class,"workflow_master_id","workflow_master_id");
    }

    public function template()
    {
        return $this->hasMany(WorkFlowTemplate::class,"workflow_template_id","workflow_template_id");
    }
}
