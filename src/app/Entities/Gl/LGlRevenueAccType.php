<?php
/**
 *Created by PhpStorm
 *Created at ৩০/৫/২১ ২:৫১ PM
 */

namespace App\Entities\Gl;


use Illuminate\Database\Eloquent\Model;

class LGlRevenueAccType extends Model
{
    protected $table = "l_gl_revenue_acc_type";
    protected $primaryKey = "revenue_acc_type_id";

    public function coa()
    {
        return $this->hasMany(GlCoa::class,"gl_type_id","gl_type_id");
    }
}
