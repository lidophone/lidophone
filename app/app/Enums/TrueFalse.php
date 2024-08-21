<?php

namespace App\Enums;

enum TrueFalse: int
{
    use Base;

    case True = 1;
    case False = 0;
}
