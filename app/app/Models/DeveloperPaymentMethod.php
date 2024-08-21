<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\DeveloperPaymentMethod
 *
 * @property int $id
 * @property int $developer_id
 * @property int $payment_method_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|DeveloperPaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeveloperPaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeveloperPaymentMethod onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|DeveloperPaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|DeveloperPaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeveloperPaymentMethod whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeveloperPaymentMethod whereDeveloperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeveloperPaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeveloperPaymentMethod wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeveloperPaymentMethod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeveloperPaymentMethod withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|DeveloperPaymentMethod withoutTrashed()
 * @mixin \Eloquent
 */
class DeveloperPaymentMethod extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const TABLE = 'developers_payment_methods';

    protected $table = self::TABLE;
}
