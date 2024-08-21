<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\HousingEstateInfrastructure
 *
 * @property int $id
 * @property int $housing_estate_id
 * @property int $infrastructure_id
 * @property string $name
 * @property int|null $time_on_foot In minutes
 * @property int|null $time_by_car In minutes
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateInfrastructure newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateInfrastructure newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateInfrastructure onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateInfrastructure query()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateInfrastructure whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateInfrastructure whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateInfrastructure whereHousingEstateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateInfrastructure whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateInfrastructure whereInfrastructureId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateInfrastructure whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateInfrastructure whereTimeByCar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateInfrastructure whereTimeOnFoot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateInfrastructure whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateInfrastructure withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateInfrastructure withoutTrashed()
 * @mixin \Eloquent
 */
class HousingEstateInfrastructure extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const TABLE = 'housing_estates_infrastructure';

    protected $table = self::TABLE;
}
