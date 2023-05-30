<?php


namespace App\Enums;


class WkReferenceTable
{
    /*** Gl Part ***/
    public const FAS_GL_TRANS_MASTER = 'FAS_GL_TRANS_MASTER';

    /*** Ap Part ***/
    public const FAS_AP_INVOICE = 'FAS_AP_INVOICE';
    public const FAS_AP_PAYMENT = 'FAS_AP_PAYMENT';
    public const FAS_AP_VENDORS = 'FAS_AP_VENDORS';
    public const FAS_AP_VENDORS_AUTH_LOG = 'FAS_AP_VENDORS_AUTH_LOG';
    public const FAS_AP_VENDORS_AUTH_LOG_ID = 'VENDORS_AUTH_LOG_ID';

    /*** Ar Part ***/
    public const FAS_AR_INVOICE = 'FAS_AR_INVOICE';
    public const FAS_AR_RECEIPT = 'FAS_AR_RECEIPT';
    public const FAS_AR_CUSTOMERS = 'FAS_AR_CUSTOMERS';
    public const FAS_AR_CUSTOMERS_AUTH_LOG = 'FAS_AR_CUSTOMERS_AUTH_LOG';

    /*** Cm Part ***/
    public const FAS_CM_CLEARING = 'FAS_CM_CLEARING';
    //FDR
    public const FAS_CM_FDR_INVESTMENT = 'FAS_CM_FDR_INVESTMENT';
    public const FAS_CM_FDR_INVESTMENT_AUTH_LOG = 'FAS_CM_FDR_INVESTMENT_AUTH_LOG';
    public const FAS_CM_FDR_PROVISION_MASTER = 'FAS_CM_FDR_PROVISION_MASTER';
    public const FAS_CM_FDR_INVESTMENT_TRANS = 'FAS_CM_FDR_INVESTMENT_TRANS';
    public const FAS_CM_FDR_MATURITY_TRANS = 'FAS_CM_FDR_MATURITY_TRANS';

    /*** Budget Management Part ***/
    //public const FAS_BUDGET_MGT_MASTER = 'FAS_BUDGET_MGT_MASTER';
    public const FAS_BUDGET_EST_MASTER = 'FAS_BUDGET_EST_MASTER';

    /*** Budget Monitoring Part ***/
    public const FAS_BUDGET_BOOKING_TRANS = 'FAS_BUDGET_BOOKING_TRANS';
    public const FAS_BUDGET_BOOKING_TRANS_LOG = 'FAS_BUDGET_BOOKING_TRANS_LOG';

}
