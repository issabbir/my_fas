<?php


namespace App\Entities\Common;


use App\Entities\Gl\GlTransMaster;
use Illuminate\Database\Eloquent\Model;

class LGlIntegrationFunctions extends Model
{
    protected $table = 'l_gl_integration_functions';
    protected $primaryKey = 'function_id';


    public function last_trans()
    {
        return $this->belongsTo(GlTransMaster::class, 'function_id','function_id')->latest('trans_master_id');
    }

}
