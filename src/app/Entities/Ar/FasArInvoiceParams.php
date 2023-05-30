<?php


namespace App\Entities\Ar;

use App\Entities\Gl\GlCoa;
use App\Entities\Gl\GlSubsidiaryParams;
use Illuminate\Database\Eloquent\Model;

class FasArInvoiceParams extends Model
{
    protected $table = 'fas_ar_invoice_params';
    protected $primaryKey = 'invoice_param_id';

    public function gl_subsidiary()
    {
        return $this->belongsTo(GlSubsidiaryParams::class,'gl_subsidiary_id','gl_subsidiary_id');
    }

    public function vat_acc()
    {
        return $this->belongsTo(GlCoa::class,'vat_gl_acc_id');
    }

    public function transaction_type(){
        //return $this->hasMany(LArTransactionType::class,'transaction_type_id','transaction_type_id');
        return $this->belongsTo(LArTransactionType::class,'transaction_type_id','transaction_type_id');
    }

}
