<?php
/**
 *Created by PhpStorm
 *Created at ১১/৯/২১ ১১:২৬ PM
 */

namespace App\Entities\Ap;


use App\Entities\Common\LApInvoiceType;
use App\Entities\Gl\GlCoa;
use App\Entities\Gl\GlCoaParams;
use App\Entities\Gl\GlSubsidiaryParams;
use Illuminate\Database\Eloquent\Model;

class FasApInvoiceParams extends Model
{
    protected $table = "fas_ap_invoice_params";
    protected $primaryKey = "invoice_param_id";

    public function gl_subsidiary()
    {
        return $this->belongsTo(GlSubsidiaryParams::class,'gl_subsidiary_id','gl_subsidiary_id');
    }

    public function invoice_type()
    {
        return $this->belongsTo(LApInvoiceType::class,'invoice_type_id','invoice_type_id');
    }

    public function tax_acc()
    {
        return $this->belongsTo(GlCoa::class,'tax_gl_acc_id');
    }

    public function vat_acc()
    {
        return $this->belongsTo(GlCoa::class,'vat_gl_acc_id');
    }

    public function sec_acc()
    {
        return $this->belongsTo(GlCoa::class,'sec_gl_acc_id');
    }

    public function distrib_line_gl_sub()
    {
        return $this->belongsTo(GlSubsidiaryParams::class,'distrib_line_gl_sub_id');
    }

    public function fine_acc()
    {
        return $this->belongsTo(GlCoa::class,'fine_gl_acc_id');
    }

    public function psi_acc()
    {
        return $this->belongsTo(GlCoa::class,'psi_gl_acc_id');
    }

    public function elec_acc()
    {
        return $this->belongsTo(GlCoa::class,'elec_gl_acc_id');
    }

    public function others_acc()
    {
        return $this->belongsTo(GlCoa::class,'others_gl_acc_id');
    }


}
