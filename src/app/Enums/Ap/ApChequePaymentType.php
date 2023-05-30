<?php
/**
 *Created Pavel
 *Created at 21-07-22
**/

namespace App\Enums\Ap;

class ApChequePaymentType
{
    public const ACCOUNT_PAYEE_CHEQUE = 1;
    public const CASH_CHEQUE = 2;

    public const ACCOUNT_PAYEE_CHEQUE_V = 'Account Payee Cheque';
    public const CASH_CHEQUE_V = 'Cash Cheque';

    /*public const  CHEQUE_PAY_TYPE_LIST = [
        '1' => 'Account Payee Cheque',
        '2' => 'Cash Cheque'
    ];*/

    public const  CHEQUE_PAY_TYPE_LIST = [
        '1' => 'Single Party Payment',
        '2' => 'Multiple Party Payment'
    ];
}
