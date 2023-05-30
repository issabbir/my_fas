<?php


namespace App\Entities\Gl;


use Illuminate\Database\Eloquent\Model;

class GlTransDetail extends Model
{
    protected $table = 'fas_gl_trans_detail';
    protected $primaryKey = 'trans_detail_id';

    public function gl_coa()
    {
        return $this->belongsTo(GlCoa::class, 'gl_acc_id','gl_acc_id');
    }
}
