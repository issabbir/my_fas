<?php


namespace App\Enums\Gl;


class CalendarStatus
{
    public const INACTIVE = "I";
    public const OPENED = "O";
    public const CLOSED = "C";
    public const OPENED_SPECIAL = "S";

    public const  STATUS = [
        'I' => 'Inactive',
        'O' => 'Open',
        'C' => 'Close',
        'S' => 'Open (Special)'
    ];
}
