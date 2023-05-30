<?php


namespace App\Entities\Cm;

use App\Entities\Gl\GlCoa;
use Illuminate\Database\Eloquent\Model;

class CmClearingParams extends Model
{
    protected $table = 'fas_cm_clearing_params';
    protected $primaryKey = 'bank_account_id';

    public function coa_info()
    {
        return $this->belongsTo(GlCoa::class,"bank_account_id","gl_acc_id");
    }

    public function clg_outward()
    {
        return $this->belongsTo(GlCoa::class,"clearing_outward_acc_id","gl_acc_id");
    }

    public function clg_inward()
    {
        return $this->belongsTo(GlCoa::class,"clearing_inward_acc_id","gl_acc_id");
    }

}
