<?php

namespace App\Enums;

enum DayOfWeek: int
{
    use Base;

    case Mo = 1;
    case Tu = 2;
    case We = 3;
    case Th = 4;
    case Fr = 5;
    case Sa = 6;
    case Su = 7;

    case MoFr = 8;
    case MoSu = 9;
}
