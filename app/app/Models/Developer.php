<?php

namespace App\Models;

use App\Enums\TrueFalse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Developer
 *
 * @property int $id
 * @property string $name
 * @property int $automatic_handling
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HousingEstate> $housingEstates
 * @property-read int|null $housing_estates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Offer> $offers
 * @property-read int|null $offers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PaymentMethod> $paymentMethods
 * @property-read int|null $payment_methods_count
 * @method static Builder|Developer newModelQuery()
 * @method static Builder|Developer newQuery()
 * @method static Builder|Developer onlyTrashed()
 * @method static Builder|Developer query()
 * @method static Builder|Developer whereAutomaticHandling($value)
 * @method static Builder|Developer whereCreatedAt($value)
 * @method static Builder|Developer whereDeletedAt($value)
 * @method static Builder|Developer whereId($value)
 * @method static Builder|Developer whereName($value)
 * @method static Builder|Developer whereUpdatedAt($value)
 * @method static Builder|Developer withTrashed()
 * @method static Builder|Developer withoutTrashed()
 * @mixin \Eloquent
 */
class Developer extends _Base
{
    use SoftDeletes;

    protected $fillable = ['name', 'automatic_handling'];

    /*
     * -----------------------------------------------------------------------------------------------------------------
     * Relationships
     * -----------------------------------------------------------------------------------------------------------------
     */

    public function housingEstates(): HasMany
    {
        return $this->hasMany(HousingEstate::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    public function paymentMethods(): BelongsToMany
    {
        return $this->belongsToMany(PaymentMethod::class, DeveloperPaymentMethod::TABLE);
    }

    /*
     * -----------------------------------------------------------------------------------------------------------------
     * Custom
     * -----------------------------------------------------------------------------------------------------------------
     */

    public static function getActive(): array
    {
        return static::whereHas('offers', function ($query) {
            $query->where('active', TrueFalse::True->value);
        })->select(['id', 'name'])->orderBy('name')->get()->toArray();
    }

    public static function getHousingEstatesIdsOfOffersLinkedToDeveloper(array $get = []): array
    {
        $developers = self::withWhereHas(
            'housingEstates',
            static fn (Builder|HasMany $housingEstates) => $housingEstates->select('id', 'developer_id')
        )
            ->whereHas(
                'offers',
                function (Builder $offers) use ($get) {
                    $offers->whereNull('housing_estate_id');
                    if (empty($get['disabled'])) {
                        $offers->where('active', TrueFalse::True->value);
                    }
                }
            )
            ->get()
            ->toArray();
        $housingEstates = array_column($developers, 'housing_estates');
        $housingEstatesIds = [];
        foreach ($housingEstates as $housingEstate) {
            $housingEstatesIds = array_merge($housingEstatesIds, array_column($housingEstate, 'id'));
        }
        return $housingEstatesIds;
    }
}
