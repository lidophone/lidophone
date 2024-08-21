<?php

namespace App\Enums;

enum RealEstateType: int
{
    use Base;

    case Apartments = 0;
    case Flat = 1;
}
