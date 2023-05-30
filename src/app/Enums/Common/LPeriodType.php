<?php


namespace App\Enums\Common;


class LPeriodType
{
    public const QUARTER = 'Q';
    public const HALF_YEAR = 'H';
    public const YEAR = 'Y';
    public const DAY = 'D';
    public const MONTH = 'M';

    public const QUARTER_TEXT = 'QUARTER';
    public const HALF_YEAR_TEXT = 'HALF_YEAR';
    public const YEAR_TEXT = 'YEAR';
    public const DAY_TEXT = 'DAY';
    public const MONTH_TEXT = 'MONTH';

    public const Actual = 'Actual (365 or 366)';
    public const Flat = 'Flat days (360)';
    public const A = 'A';
    public const F = 'F';
}
