<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\HousingEstatePromotion
 *
 * @property int $id
 * @property int $housing_estate_id
 * @property int $promotion_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePromotion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePromotion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePromotion onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePromotion query()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePromotion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePromotion whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePromotion whereHousingEstateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePromotion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePromotion wherePromotionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePromotion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePromotion withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePromotion withoutTrashed()
 * @mixin \Eloquent
 */
class HousingEstatePromotion extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const TABLE = 'housing_estates_promotions';

    protected $table = self::TABLE;
}
