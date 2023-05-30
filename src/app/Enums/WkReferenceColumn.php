<?php


namespace App\Enums;


class WkReferenceColumn
{
    /*** Gl Part ***/
    public const TRANS_MASTER_ID = 'TRANS_MASTER_ID';

    /*** Ap Part ***/
    public const INVOICE_ID = 'INVOICE_ID';
    public const PAYMENT_ID = 'PAYMENT_ID';
    public const VENDOR_ID = 'VENDOR_ID';
    public const VENDORS_AUTH_LOG_ID = 'VENDORS_AUTH_LOG_ID';

    /*** Ar Part ***/
    public const AR_INVOICE_ID = 'INVOICE_ID';
    public const AR_RECEIPT_ID = 'RECEIPT_ID';
    public const CUSTOMER_ID = 'CUSTOMER_ID';
    public const CUSTOMER_AUTH_LOG_ID = 'CUSTOMER_AUTH_LOG_ID';

    /*** Cm Part ***/
    public const CLEARING_ID = 'CLEARING_ID';
    //
    public const INVESTMENT_ID = 'INVESTMENT_ID';
    public const INVESTMENT_AUTH_LOG_ID = 'INVESTMENT_AUTH_LOG_ID';
    public const PROVISION_MASTER_ID = 'PROVISION_MASTER_ID';
    public const INVESTMENT_TRANS_ID = 'INVESTMENT_TRANS_ID';
    public const MATURITY_TRANS_ID = 'MATURITY_TRANS_ID';
    /*** Budget Management Part ***/
    public const BUDGET_MASTER_ID = 'BUDGET_MASTER_ID';

    /*** Budget Monitoring Part ***/
    public const BUDGET_BOOKING_ID = 'BUDGET_BOOKING_ID';
    public const BUDGET_BOOK_TRAN_LOG_ID = 'BUDGET_BOOK_TRAN_LOG_ID';
}
