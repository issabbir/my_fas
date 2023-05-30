<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 2/17/21
 * Time: 12:46 PM
 */

namespace App\Enums\Ap;


class ApFunType
{
    /** This is child function id for l_gl_integration_functions table AP_MODULE **/
    public const AP_INVOICE_BILL_ENTRY = 3101;
    public const AP_INVOICE_BILL_PAYMENT = 3102;
    public const AP_SALARY_ENTRY = 212;

    public const CM_FDR_OPENING_TRANSACTION = 4103;
}
