<?php
/**
 *Created by PhpStorm
 *Created at ২৩/৯/২১ ৫:২০ PM
 */

namespace App\Entities\Ap;


use Illuminate\Database\Eloquent\Model;

class FasApInvoiceDoc extends Model
{
    protected $table = "fas_ap_invoice_docs";
    protected $primaryKey = "invoice_id";
}
