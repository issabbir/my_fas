<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 2/17/21
 * Time: 12:46 PM
 */

namespace App\Enums\Common;


class LGlInteFun
{
    /** This are parent function id for l_gl_integration_functions table
     *  1. GL_MODULE
     *  2. AP_MODULE
     *  3. CM_MODULE
     *  4. AR_MODULE
    **/

    //Gl-Part
    public const CASH_REC_VOUCHER  = 1100;
    public const CASH_PAY_VOUCHER  = 1200;
    public const CASH_TRANS_VOUCHER = 1300;
    public const JOURNAL_VOUCHER = 1400;

    //Ap-Part
    public const AP_ACCOUNT_PAYABLE  = 3100;

    //Ar-Part
    public const AR_ACCOUNT_RECEIVABLE = 2100;

    //Cm-Part
    public const CM_CLEARING_RECON_PROCESS  = 4100;

    //Budget Monitoring
    public const BUDGET_MONITORING_SYSTEM = 9100;
}
