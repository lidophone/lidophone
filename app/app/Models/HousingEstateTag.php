<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\HousingEstateTag
 *
 * @property int $id
 * @property int $housing_estate_id
 * @property int $tag_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateTag onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateTag query()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateTag whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateTag whereHousingEstateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateTag whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateTag whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateTag withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstateTag withoutTrashed()
 * @mixin \Eloquent
 */
class HousingEstateTag extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const TABLE = 'housing_estates_tags';

    protected $table = self::TABLE;
}
