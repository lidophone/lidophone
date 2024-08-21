<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Marketplace
 *
 * @property int $id
 * @property string $name
 * @property int $expert_mode
 * @property int $other_developers
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Marketplace newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Marketplace newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Marketplace onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Marketplace query()
 * @method static \Illuminate\Database\Eloquent\Builder|Marketplace whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Marketplace whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Marketplace whereExpertMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Marketplace whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Marketplace whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Marketplace whereOtherDevelopers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Marketplace whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Marketplace withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Marketplace withoutTrashed()
 * @mixin \Eloquent
 */
class Marketplace extends Model
{
    use HasFactory;
    use SoftDeletes;
}
