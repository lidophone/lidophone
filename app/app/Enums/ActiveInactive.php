<?php

namespace App\Enums;

enum ActiveInactive: int
{
    use Base;

    case Active = 1;
    case Inactive = 0;
}
