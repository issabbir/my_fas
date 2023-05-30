<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 09/12/2021
 * Time: 2:27 PM
 */

namespace App\Contracts\Ap;


interface ApLookupContract
{
    public function getPartySubLedger($functionId = null, $intBillPayYn = null);

}
