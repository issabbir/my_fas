<?php


namespace App\Enums;


class WorkFlowRoleKey
{

    //Gl Part
    public const GL_RECEIVE_VOUCHER_MAKE = 'GL_RECEIVE_VOUCHER_MAKE';
    public const GL_PAYMENT_VOUCHER_MAKE = 'GL_PAYMENT_VOUCHER_MAKE';
    public const GL_TRANSFER_VOUCHER_MAKE = 'GL_TRANSFER_VOUCHER_MAKE';
    public const GL_JOURNAL_VOUCHER_MAKE = 'GL_JOURNAL_VOUCHER_MAKE';

    public const GL_RECEIVE_VOUCHER_AUTHORIZE = 'GL_RECEIVE_VOUCHER_AUTHORIZE';
    public const GL_PAYMENT_VOUCHER_AUTHORIZE = 'GL_PAYMENT_VOUCHER_AUTHORIZE';
    public const GL_TRANSFER_VOUCHER_AUTHORIZE = 'GL_TRANSFER_VOUCHER_AUTHORIZE';
    public const GL_JOURNAL_VOUCHER_AUTHORIZE = 'GL_JOURNAL_VOUCHER_AUTHORIZE';

    //cpa_security.sec_role keys (without workflow)
    public const CAN_CANCEL_GL_TRANSFER_VOUCHER = 'CANCEL_GL_TRANSFER_VOUCHER';
    public const CAN_CANCEL_GL_PAYMENT_VOUCHER = 'CANCEL_GL_PAYMENT_VOUCHER';
    public const CAN_CANCEL_GL_RECEIVE_VOUCHER = 'CANCEL_GL_RECEIVE_VOUCHER';
    public const CAN_CANCEL_GL_JOURNAL_VOUCHER = 'CANCEL_GL_JOURNAL_VOUCHER';

    // Ap Part
    public const AP_INVOICE_BILL_ENTRY_MAKE = 'AP_INVOICE_BILL_ENTRY_MAKE';
    public const AP_INVOICE_BILL_PAYMENT_MAKE = 'AP_INVOICE_BILL_PAYMENT_MAKE';

    public const AP_INVOICE_BILL_ENTRY_AUTHORIZE = 'AP_INVOICE_BILL_ENTRY_AUTHORIZE';
    public const AP_INVOICE_BILL_PAYMENT_AUTHORIZE = 'AP_INVOICE_BILL_PAYMENT_AUTHORIZE';

    //cpa_security.sec_role keys (without workflow)
    public const CAN_CANCEL_AP_ENTRY_VOUCHER = 'CANCEL_AP_ENTRY_VOUCHER';
    public const CAN_CANCEL_AP_PAYMENT_VOUCHER = 'CANCEL_AP_PAYMENT_VOUCHER';

    // Ar Part
    public const AR_INVOICE_BILL_ENTRY_MAKE = 'AR_INVOICE_BILL_ENTRY_MAKE';
    public const AR_INVOICE_BILL_RECEIPT_MAKE = 'AR_INVOICE_BILL_RECEIPT_MAKE';

    public const AR_INVOICE_BILL_ENTRY_AUTHORIZE = 'AR_INVOICE_BILL_ENTRY_AUTHORIZE';
    public const AR_INVOICE_BILL_RECEIPT_AUTHORIZE = 'AR_INVOICE_BILL_RECEIPT_AUTHORIZE';

    //cpa_security.sec_role keys (without workflow)
    public const CAN_CANCEL_AR_ENTRY_VOUCHER = 'CANCEL_AR_ENTRY_VOUCHER';
    public const CAN_CANCEL_AR_RECEIPT_VOUCHER = 'CANCEL_AR_RECEIPT_VOUCHER';

    // Cm Part
    public const CM_FDR_MATURITY_TRANS_MAKE = 'CM_FDR_MATURITY_TRANS_MAKE';

    //Budget MGT Part
    public const BUDGET_MGT_DEPARTMENT_REVIEW = 'BUDGET_MGT_DEPARTMENT_REVIEW';

}
