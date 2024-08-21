<?php

namespace App\Helpers;

class FormatHelper
{
    public const REGEXP_PRICE_FORMAT = '\B(?=(\d{3})+(?!\d))';

    public const FIXED_NUMBER_OF_ZEROS = 6;

    public static function trimNDigits(int $number, int $numberOfDigitsToTrim): int
    {
        $number = (string) $number;
        $number = preg_replace('~(\d{' . $numberOfDigitsToTrim . '})$~', '.$1', $number, -1, $count);
        if ($count === 0) {
            return 0;
        }
        $number = round($number);
        return (int) $number;
    }

    public static function formatPrice(string $price): string
    {
        return preg_replace('~' . self::REGEXP_PRICE_FORMAT . '~', ' ', $price);
    }

    public static function getFixedNumberOfZerosAsString(): string
    {
        return str_repeat('0', FormatHelper::FIXED_NUMBER_OF_ZEROS);
    }

    public static function formatDateTime(string $dateTime): string
    {
        return date('Y.m.d H:i:s', strtotime($dateTime));
    }
}
