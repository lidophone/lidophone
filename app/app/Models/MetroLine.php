<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\MetroLine
 *
 * @property int $id
 * @property int|null $city_id
 * @property string $name
 * @property string $color
 * @property string|null $designation
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\City|null $city
 * @method static \Illuminate\Database\Eloquent\Builder|MetroLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MetroLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MetroLine onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MetroLine query()
 * @method static \Illuminate\Database\Eloquent\Builder|MetroLine whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetroLine whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetroLine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetroLine whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetroLine whereDesignation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetroLine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetroLine whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetroLine whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetroLine withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MetroLine withoutTrashed()
 * @mixin \Eloquent
 */
class MetroLine extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public static function getSelectOptions(int $cityId = null): array
    {
        $query = self::select(['id', 'name']);
        if ($cityId) {
            $query->where('city_id', $cityId);
        }
        return $query->orderBy('name')->pluck('name', 'id')->toArray();
    }
}
