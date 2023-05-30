<?php
/**
 *Created by PhpStorm
 *Created at ৩০/৫/২১ ৩:০৫ PM
 */

namespace App\Entities\Gl;


use Illuminate\Database\Eloquent\Model;

class GlCashAccParams extends Model
{
    //protected $table = "fas_gl_cash_acc_params";
    protected $table = "fas_gl_acc_params_cash";
    protected $primaryKey = "cash_acc_type_id";

    public function cash_type()
    {
        return $this->belongsTo(LGlCashAccType::class,"cash_acc_type_id","cash_acc_type_id");
    }

    public function gl_acc()
    {
        return $this->belongsTo(GlCoa::class,"gl_acc_id","gl_acc_id");
    }
}
