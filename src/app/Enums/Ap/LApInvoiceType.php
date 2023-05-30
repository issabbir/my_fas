<?php
/**
 *Created by PhpStorm
 *Created at ৬/৯/২১ ১:২৪ PM
 */

namespace App\Enums\Ap;

class LApInvoiceType{
    public const STANDARD='1';
    public const DEBIT_MEMO='2';
    public const CREDIT_MEMO='3';
    public const INTERNAL_BILLS ='4';
    public const IMPREST_REVOLVING_CASH ='5';
    public const ADVANCES_PREPAYMENTS ='6';
    public const PROVISION_EXPENSES ='7';
    public const ADJUSTMENT_BILLS ='8';
    /*public const ADJUSTMENT_JOURNAL ='9';
    public const COLLECTION_AGENT ='10';*/
    public const MIS_ADJ_DEBIT_MEMO_JV ='9';
    public const MIS_ADJ_CREDIT_MEMO_JV ='10';
    public const ADJ_PRO_CONTRA_SUPP ='11';
    public const ADJ_PRO_DPT_EMP ='12';
    public const SWC_ADJ_PRO_CON_SUPP ='13';
    public const REF_SEC_DEP ='14';


    /* previous data

    public const REFUND='4'; // previous value 4 represent REFUND but now Internal_Bills
    public const PREPAYMENT='5';
    public const ADVANCE_PAY='6';
    public const ADVANCE_ADJUST='7';
    public const IMPREST_PAY='8';
    public const IMPREST_ADJUST='9';

    */
}
