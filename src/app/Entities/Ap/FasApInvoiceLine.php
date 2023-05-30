<?php
/**
 *Created by PhpStorm
 *Created at ২৩/৯/২১ ৫:০১ PM
 */

namespace App\Entities\Ap;


use App\Entities\Gl\GlCoa;
use Illuminate\Database\Eloquent\Model;

class FasApInvoiceLine extends Model
{
    protected $table = "fas_ap_invoice_lines";
    protected $primaryKey = "invoice_id";

    public function gl_acc_detail()
    {
        return $this->belongsTo(GlCoa::class, 'gl_acc_id','gl_acc_id');

    }
}
