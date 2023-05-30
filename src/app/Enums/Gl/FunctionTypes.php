<?php
/**
 *Created by PhpStorm
 *Created at ১/৬/২১ ২:৩৬ PM
 */

namespace App\Enums\Gl;


class FunctionTypes
{
    /** This is child function id for l_gl_integration_functions table GL_MODULE **/

    public const BANK_RECEIVE = "1101";
    public const CASH_RECEIVE = "1102";

    public const BANK_PAYMENT = "1201";
    public const CASH_PAYMENT = "1202";

    public const BANK_TRANSFER = "1301";
    public const CASH_WITHDRAWL = "1302"; //Remove it

}
