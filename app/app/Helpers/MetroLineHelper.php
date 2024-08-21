<?php

namespace App\Helpers;

class MetroLineHelper
{
    public static function getColorBox(string $color): string
    {
        return '<div class="metro-line-color" style="background: ' . $color . ';">';
    }
}
