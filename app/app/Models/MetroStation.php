<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Znck\Eloquent\{
    Traits\BelongsToThrough as BelongsToThroughTrait,
    Relations\BelongsToThrough as BelongsToThroughRelation,
};

/**
 * App\Models\MetroStation
 *
 * @property int $id
 * @property int|null $metro_line_id
 * @property string $name
 * @property int $under_construction
 * @property float|null $latitude
 * @property float|null $longitude
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\MetroLine|null $metroLine
 * @method static \Illuminate\Database\Eloquent\Builder|MetroStation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MetroStation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MetroStation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MetroStation query()
 * @method static \Illuminate\Database\Eloquent\Builder|MetroStation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetroStation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetroStation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetroStation whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetroStation whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetroStation whereMetroLineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetroStation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetroStation whereUnderConstruction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetroStation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetroStation withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MetroStation withoutTrashed()
 * @mixin \Eloquent
 */
class MetroStation extends Model
{
    use HasFactory, BelongsToThroughTrait;
    use SoftDeletes;

    public function metroLine(): BelongsTo
    {
        return $this->belongsTo(MetroLine::class);
    }

    public function city(): BelongsToThroughRelation
    {
        return $this->belongsToThrough(City::class, MetroLine::class);
    }

    /**
     * Example
     */
    // public function housingEstates(): BelongsToMany
    // {
    //     return $this->belongsToMany(
    //         HousingEstate::class,
    //         'housing_estates_metro_stations',
    //         // The following fields  are not required: https://i.stack.imgur.com/6fNCt.png
    //         'metro_station_id',
    //         'housing_estate_id',
    //     );
    // }
}
