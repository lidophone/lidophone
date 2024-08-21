<?php

namespace App\Enums;

trait Base
{
    public static function getSelectOptions(): array
    {
        $cases = self::cases();
        $options = [];
        foreach ($cases as $case) {
            $options[$case->value] = self::getName($case->value);
        }
        return $options;
    }

    public static function getName(int $value): string
    {
        $name = self::from($value)->name;
        if (defined('self::PREFIX')) {
            $name = str_replace(self::PREFIX, '', $name);
        }
        if (defined('self::NAME_REPLACEMENTS')) {
            $name = str_replace(array_keys(self::NAME_REPLACEMENTS), array_values(self::NAME_REPLACEMENTS), $name);
        }
        return __(str_replace('_', ' ', $name));
    }
}
