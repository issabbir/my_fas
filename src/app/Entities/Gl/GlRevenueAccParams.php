<?php
/**
 *Created by PhpStorm
 *Created at ৩০/৫/২১ ৩:০৬ PM
 */

namespace App\Entities\Gl;


use Illuminate\Database\Eloquent\Model;

class GlRevenueAccParams extends Model
{
    //protected $table = "fas_gl_revenue_acc_params";
    protected $table = "fas_gl_acc_params_revenue";
    protected $primaryKey = "revenue_acc_type_id";

    public function revenue_type()
    {
        return $this->belongsTo(LGlRevenueAccType::class,"revenue_acc_type_id","revenue_acc_type_id");
    }

    public function gl_acc()
    {
        return $this->belongsTo(GlCoa::class,"gl_acc_id","gl_acc_id");
    }
}
