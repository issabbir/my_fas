<?php


namespace App\Entities\Gl;

use App\Entities\Common\LBillRegister;
use App\Entities\Common\LBillSection;
use App\Entities\Common\LCostCenterDept;
use App\Entities\Common\LGlIntegrationFunctions;
use App\Entities\Common\VwDepartment;
use App\Entities\WorkFlowMapping;
use Illuminate\Database\Eloquent\Model;

class GlTransMaster extends Model
{
    protected $table = 'fas_gl_trans_master';
    protected $primaryKey = 'trans_master_id';

    public function fun_type()
    {
        return $this->belongsTo(LGlIntegrationFunctions::class, 'function_id','function_id');
    }

    public function bill_sec()
    {
        return $this->belongsTo(LBillSection::class, 'bill_sec_id','bill_sec_id');
    }

    public function bill_reg()
    {
        return $this->belongsTo(LBillRegister::class, 'bill_reg_id','bill_reg_id');
    }

    public function dept()
    {
        return $this->belongsTo(LCostCenterDept::class, 'department_id','cost_center_dept_id');
    }

    public function approval_status()
    {
        return $this->belongsTo(WorkFlowMapping::class, 'workflow_mapping_id','workflow_mapping_id');
    }

    public function attachments()
    {
        return $this->hasMany(GlTransDocs::class,'trans_master_id','trans_master_id');
    }

}
