<?php

namespace App\Enums;

enum Finishing: int
{
    use Base;

    protected const NAME_REPLACEMENTS = [
        'Pre_finishing' => 'Pre-finishing',
    ];

    case Without_finishing = 0;
    case Pre_finishing = 1;
    case With_renovation = 2;
}
