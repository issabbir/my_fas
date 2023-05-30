<?php


namespace App\Entities\Ar;

use App\Entities\Ap\FasApVendorAddress;
use App\Entities\Common\LApVendorCategory;
use Illuminate\Database\Eloquent\Model;

class FasArCustomers extends Model
{
    protected $table = 'fas_ar_customers';
    protected $primaryKey = 'customer_id';

    public function customer_category()
    {
        return $this->belongsTo(LArCustomerCategory::class,'customer_category_id','customer_category_id');
    }

    public function customer_address()
    {
        return $this->hasOne(FasArCustomersAddress::class,'customer_id','customer_id');
    }
}
