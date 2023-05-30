<?php
/**
 * Created by PhpStorm.
 * User: ashraf
 * Date: 1/26/20
 * Time: 12:46 PM
 */

namespace App\Enums;


class ApprovalStatus
{
    public const PENDING = 'P';
    public const APPROVED = 'A';
    public const REJECT = 'R';
    public const CANCEL = 'C';
    public const DRAFT = 'D';

    public const  APPROVAL_STATUS = [
        'P' => 'Pending',
        'A' => 'Approved',
        'R' => 'Reject'
    ];

    public const NEW = 'I';
    public const MAKE = 'M';
    public const EDIT = 'E';
    public const DELETE = 'D';

    public const  AUTHORIZE_FUN_TYPE = [
        'I' => 'New',
        'M' => 'Make',
        'E' => 'Edit',
        'D' => 'Delete'
    ];

    public const  CRUD_ACTION = [
        'I' => 'New',
        'U' => 'Edit',
        'D' => 'Delete'
    ];

    /*** BUDGET WORKFLOW STATUS PART ***/
    public const WK_INITIALIZED = 1;
    public const WK_DEPARTMENT_REVIEWED = 2;
    public const WK_FINANCE_REVIEWED = 3;
    public const WK_BOARD_APPROVED = 4;
    public const WK_MINISTRY_APPROVED = 5;
}
