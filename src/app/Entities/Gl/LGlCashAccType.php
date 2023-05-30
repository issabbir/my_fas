<?php
/**
 *Created by PhpStorm
 *Created at ৩০/৫/২১ ২:৫০ PM
 */

namespace App\Entities\Gl;


use Illuminate\Database\Eloquent\Model;

class LGlCashAccType extends Model
{
    protected $table = "l_gl_cash_acc_type";
    protected $primaryKey = "cash_acc_type_id";

    public function coa()
    {
        return $this->hasMany(GlCoa::class,"gl_type_id","gl_type_id");
    }
}
