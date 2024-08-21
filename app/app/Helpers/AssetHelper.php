<?php

namespace App\Helpers;

class AssetHelper
{
    public static function addFilemtime(string $publicPath): string
    {
        $filemtime = filemtime(public_path() . $publicPath);
        return $publicPath . '?v=' . $filemtime;
    }
}
