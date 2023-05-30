<?php


namespace App\Entities\Ar;

use Illuminate\Database\Eloquent\Model;

class FasArInvoiceLines extends Model
{
    protected $table = 'fas_ar_invoice_lines';
    protected $primaryKey = 'invoice_line_id';

}
