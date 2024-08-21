<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\HousingEstateMetroStation
 *
 * @property int $id
 * @property int $housing_estate_id
 * @property int $metro_station_id
 * @property int|null $time_on_foot In minutes
 * @property int|null $time_by_car In minutes
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateMetroStation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateMetroStation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateMetroStation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateMetroStation query()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateMetroStation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateMetroStation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateMetroStation whereHousingEstateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateMetroStation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateMetroStation whereMetroStationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateMetroStation whereTimeByCar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateMetroStation whereTimeOnFoot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateMetroStation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateMetroStation withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateMetroStation withoutTrashed()
 * @mixin \Eloquent
 */
class HousingEstateMetroStation extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const TABLE = 'housing_estates_metro_stations';

    protected $table = self::TABLE;
}
