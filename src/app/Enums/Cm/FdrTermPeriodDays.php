<?php


namespace App\Enums\Cm;


class FdrTermPeriodDays
{
    public const TERM_DAYS = [
        ['period' => 365],
        ['period' => 360]
    ];

    public const Actual = 'Actual (365 or 366)';
    public const Flat = 'Flat (360)';
    public const A = 'A';
    public const F = 'F';
}
