<?php


namespace App\Entities\Ar;

use App\Entities\Ap\FasApVendorAddress;
use App\Entities\Common\LApVendorCategory;
use Illuminate\Database\Eloquent\Model;

class FasArCustomersAuthLog extends Model
{
    protected $table = 'fas_ar_customers_auth_log';
    protected $primaryKey = 'customer_auth_log_id';


}
