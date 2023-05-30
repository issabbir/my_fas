<?php
/**
 *Created by PhpStorm
 *Created at ২২/৯/২১ ৫:১২ PM
 */

namespace App\Entities\Ap;


use App\Entities\Common\LApInvoiceStatus;
use App\Entities\Common\LApInvoiceType;
use App\Entities\Common\LBillRegister;
use App\Entities\Common\LBillSection;
use Illuminate\Database\Eloquent\Model;

class FasApInvoice extends Model
{
    protected $table = "fas_ap_invoice";

    public function invoice_status()
    {
        return $this->belongsTo(LApInvoiceStatus::class, 'invoice_status_id', 'invoice_status_id');
    }

    public function vendor()
    {
        return $this->belongsTo(FasApVendors::class, 'vendor_id', 'vendor_id');
    }

    public function bill_section()
    {
        return $this->belongsTo(LBillSection::class, 'bill_sec_id');
    }

    public function bill_reg()
    {
        return $this->belongsTo(LBillRegister::class,'bill_reg_id');
    }

    public function invoice_type()
    {
        return $this->belongsTo(LApInvoiceType::class,'invoice_type_id');
    }

    public function invoice_line()
    {
        return $this->hasMany(FasApInvoiceLine::class,'invoice_id','invoice_id');
    }

    public function invoice_file()
    {
        return $this->hasMany(FasApInvoice::class,'invoice_id');
    }
}
