<?php

namespace App\Enums;

enum Deadline: int
{
    use Base;

    private const MAX_DEADLINE_YEAR = 10;

    case I = 1;
    case II = 2;
    case III = 3;
    case IV =4;

    public static function getYearSelectOption(): array
    {
        $currentYear = (int) date('Y');
        $deadlineYears = range($currentYear, $currentYear + self::MAX_DEADLINE_YEAR);
        return array_combine($deadlineYears, $deadlineYears);
    }

    public static function getQuarterSelectOption(): array
    {
        return array_column(self::cases(), 'value', 'value');
    }
}
