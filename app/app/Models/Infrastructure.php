<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Infrastructure
 *
 * @property int $id
 * @property string $designation
 * @property string|null $icon_filename
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Infrastructure newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Infrastructure newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Infrastructure onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Infrastructure query()
 * @method static \Illuminate\Database\Eloquent\Builder|Infrastructure whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Infrastructure whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Infrastructure whereDesignation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Infrastructure whereIconFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Infrastructure whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Infrastructure whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Infrastructure withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Infrastructure withoutTrashed()
 * @mixin \Eloquent
 */
class Infrastructure extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'infrastructure';
}
