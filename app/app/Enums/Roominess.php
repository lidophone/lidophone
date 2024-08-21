<?php

namespace App\Enums;

enum Roominess: int
{
    use Base;

    private const PREFIX = 'TYPE_';

    case TYPE_C = 1;
    case TYPE_1K = 2;
    case TYPE_2E = 3;
    case TYPE_2K = 4;
    case TYPE_3E = 5;
    case TYPE_3K = 6;
    case TYPE_4E = 7;
    case TYPE_4K = 8;
    case TYPE_5E = 9;
    case TYPE_5K = 10;

    case TYPE_C_DPLX = 11;
    case TYPE_1K_DPLX = 12;
    case TYPE_2E_DPLX = 13;
    case TYPE_2K_DPLX = 14;

    public static function getDplxValues(): array
    {
        return [
            self::TYPE_C_DPLX->value,
            self::TYPE_1K_DPLX->value,
            self::TYPE_2E_DPLX->value,
            self::TYPE_2K_DPLX->value,
        ];
    }
}
