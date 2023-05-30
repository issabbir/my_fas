<?php


namespace App\Entities\Cm;

use App\Entities\Gl\GlCoa;
use Illuminate\Database\Eloquent\Model;

class CmChequeBooks extends Model
{
    protected $table = 'fas_cm_cheque_books';
    protected $primaryKey = 'chq_book_id';

    public function coa_info()
    {
        return $this->belongsTo(GlCoa::class,"bank_gl_acc_id","gl_acc_id");
    }
}
