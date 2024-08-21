<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\HousingEstatePaymentMethod
 *
 * @property int $id
 * @property int $housing_estate_id
 * @property int $payment_method_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePaymentMethod onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePaymentMethod whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePaymentMethod whereHousingEstateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePaymentMethod wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePaymentMethod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePaymentMethod withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|HousingEstatePaymentMethod withoutTrashed()
 * @mixin \Eloquent
 */
class HousingEstatePaymentMethod extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const TABLE = 'housing_estates_payment_methods';

    protected $table = self::TABLE;
}
