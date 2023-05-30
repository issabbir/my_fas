<?php


namespace App\Entities;


use App\Entities\Security\Role;
use Illuminate\Database\Eloquent\Model;

class WorkFlowTemplate extends Model
{
    protected $table = "workflow_template";

    public function mapping()
    {
        return $this->hasMany(WorkFlowMapping::class,"workflow_template_id","workflow_template_id");
    }

    public function template_wise_map()
    {
        return $this->hasOne(WorkFlowMapping::class,"workflow_template_id","workflow_template_id");
    }

    public function master()
    {
        return $this->belongsTo(WorkFlowMaster::class,"workflow_master_id","workflow_master_id");
    }

    public function sec_role()
    {
        return $this->belongsTo(Role::class,"step_role_key","role_key");
    }
}
