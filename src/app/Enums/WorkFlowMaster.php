<?php


namespace App\Enums;


class WorkFlowMaster
{
    //Gl Part
    public const GL_RECEIVE_VOUCHER_APPROVAL = 1;
    public const GL_PAYMENT_VOUCHER_APPROVAL = 2;
    public const GL_TRANSFER_VOUCHER_APPROVAL = 3;
    public const GL_JOURNAL_VOUCHER_APPROVAL = 4;

    // Ap Part
    public const AP_INVOICE_BILL_ENTRY_APPROVAL = 5;
    public const AP_INVOICE_BILL_PAYMENT_APPROVAL = 6;
    public const AP_VENDOR_ENTRY_APPROVAL = 11;
    //public const AP_INV_BILL_ENTRY_WITH_WITHOUT_BUD_BOOK_MST = 14;

    // Ar Part
    public const AR_INVOICE_BILL_ENTRY_APPROVAL = 8;
    public const AR_INVOICE_BILL_RECEIPT_APPROVAL = 9;
    public const AR_CUSTOMER_ENTRY_APPROVAL = 10;

    // Cm Part
    public const AP_CLEARING_RECONCILIATION_APPROVAL = 7;
    //FDR
    public const CM_FDR_INVESTMENT_REGISTER = 16;
    public const CM_FDR_OPENING_TRANSACTION = 17;
    public const CM_FDR_INTEREST_PROVISION_PROCESS = 18;
    public const CM_FDR_MATURITY_TRANSACTION = 19;


    //Budget MGT Part
    public const BUDGET_MGT_BUDGET_INITIALIZATION_APPROVAL = 12;

    /*** Budget Monitoring Part ***/
    public const BUDGET_MON_BUDGET_CONCURRENCE_TRANSACTION_APPROVAL = 13;
    public const BUDGET_MON_CONCURRENCE_TRANS_EDIT_AUTHORIZE = 14;
    public const BUDGET_MON_CONCURRENCE_TRANS_DELETE_AUTHORIZE = 15;


}
