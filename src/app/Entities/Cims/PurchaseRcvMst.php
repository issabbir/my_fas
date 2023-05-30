<?php


namespace App\Entities\Cims;


use App\Entities\Ap\ApSupplierSites;
use App\Entities\Ap\Supplier;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class PurchaseRcvMst extends Model
{
    protected $table = "CIMS.PURCHASE_RCV_MST";
    protected $primaryKey = "purchase_rcv_mst_id";

    public function purchase_rcv_dtl()
    {
        return $this->hasMany(PurchaseRcvDtl::class,"purchase_rcv_mst_id","purchase_rcv_mst_id");
    }

    public function vendor()
    {
        return $this->belongsTo(Supplier::class,"supplier_id","vendor_id");
    }

    public function vendor_sites()
    {
        return $this->hasOne(ApSupplierSites::class,"vendor_id","supplier_id");
    }
}
