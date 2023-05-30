<?php

namespace App\Entities\Ap;

use Illuminate\Database\Eloquent\Model;

class FasApPaymentDocs extends Model
{
    protected $table = "fas_ap_payment_docs";
    protected $primaryKey = "doc_file_id";

}
