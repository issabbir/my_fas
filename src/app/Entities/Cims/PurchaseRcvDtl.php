<?php


namespace App\Entities\Cims;


use Illuminate\Database\Eloquent\Model;

class PurchaseRcvDtl extends Model
{
    protected $table = "CIMS.PURCHASE_RCV_DTL";
    protected $primaryKey = "purchase_rcv_dtl_id";
    protected $with = ["item"];
    public function purchase_rcv_mst()
    {
        return $this->belongsTo(PurchaseRcvMst::class, "purchase_rcv_mst_id","purchase_rcv_mst_id");
    }

    public function item()
    {
        return $this->belongsTo(LItem::class,"item_id","item_id");
    }

}
