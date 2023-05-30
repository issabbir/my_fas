<?php


namespace App\Entities\Gl;


use Illuminate\Database\Eloquent\Model;

class GlSubsidiaryParams extends Model
{
    //protected $table = 'fas_gl_subsidiary_params';
    protected $table = 'fas_gl_sub_params';
    protected $primaryKey = 'gl_subsidiary_id';

    public function coa_info()
    {
        /*** Previous Method block- pavel:07-04-22 ***/
        //return $this->belongsTo(GlCoa::class, 'gl_subsidiary_acc_id','gl_acc_id');

        /*** Add this method- Pavel:07-04-22 ***/
        return $this->belongsTo(GlCoa::class, 'gl_acc_id','gl_acc_id');
    }
}
