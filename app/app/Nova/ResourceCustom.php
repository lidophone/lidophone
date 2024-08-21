<?php

namespace App\Nova;

use Illuminate\Database\Eloquent\Builder;

abstract class ResourceCustom extends Resource
{
    public static $clickAction = 'ignore';

    public static $perPageViaRelationship = 10;

    /**
     * @see https://github.com/laravel/nova-issues/issues/156#issuecomment-773375308
     */
    protected static function applyOrderings($query, array $orderings): Builder
    {
        if (empty(array_filter($orderings)) && property_exists(static::class, 'orderBy')) {
            $orderings = static::$orderBy;
        }
        return parent::applyOrderings($query, $orderings);
    }
}
