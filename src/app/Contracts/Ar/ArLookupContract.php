<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 09/12/2021
 * Time: 2:27 PM
 */

namespace App\Contracts\Ar;


interface ArLookupContract {

    public function getCustomers($searchQ=null);

    public function getInvoiceStatus();

}
