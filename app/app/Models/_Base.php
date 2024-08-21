<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class _Base extends Model
{
    use HasFactory;

    public static function getSelectOptions(): array
    {
        return self::select(['id', 'name'])->orderBy('name')->pluck('name', 'id')->toArray();
    }

    public static function getSelectOptionsAsAssocArray(): array
    {
        return self::select(['id', 'name'])->orderBy('name')->get()->toArray();
    }
}
