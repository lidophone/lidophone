<?php

namespace App\Models;

use App\Enums\TrueFalse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Objects
 *
 * @property int $id
 * @property int $housing_estate_id
 * @property int $roominess
 * @property int $real_estate_type
 * @property int $finishing
 * @property float $square_meters
 * @property int $price
 * @property int $done
 * @property int|null $deadline_year
 * @property int|null $deadline_quarter
 * @property string|null $yandex_realty_internal_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\HousingEstate $housingEstate
 * @method static Builder|Objects newModelQuery()
 * @method static Builder|Objects newQuery()
 * @method static Builder|Objects onlyTrashed()
 * @method static Builder|Objects query()
 * @method static Builder|Objects whereCreatedAt($value)
 * @method static Builder|Objects whereDeadlineQuarter($value)
 * @method static Builder|Objects whereDeadlineYear($value)
 * @method static Builder|Objects whereDeletedAt($value)
 * @method static Builder|Objects whereDone($value)
 * @method static Builder|Objects whereFinishing($value)
 * @method static Builder|Objects whereHousingEstateId($value)
 * @method static Builder|Objects whereId($value)
 * @method static Builder|Objects wherePrice($value)
 * @method static Builder|Objects whereRealEstateType($value)
 * @method static Builder|Objects whereRoominess($value)
 * @method static Builder|Objects whereSquareMeters($value)
 * @method static Builder|Objects whereUpdatedAt($value)
 * @method static Builder|Objects whereYandexRealtyInternalId($value)
 * @method static Builder|Objects withTrashed()
 * @method static Builder|Objects withoutTrashed()
 * @mixin \Eloquent
 */
class Objects extends _Base // [!] DON'T RENAME TO "OBJECT" BECAUSE IT'S A RESERVED WORD
{
    use SoftDeletes;

    /*
     * -----------------------------------------------------------------------------------------------------------------
     * Relationships
     * -----------------------------------------------------------------------------------------------------------------
     */

    public function housingEstate(): BelongsTo
    {
        return $this->belongsTo(HousingEstate::class);
    }

    /*
     * -----------------------------------------------------------------------------------------------------------------
     * Custom
     * -----------------------------------------------------------------------------------------------------------------
     */

    public static function getMinMaxPriceOfActiveOffers(): array
    {
        $query = Objects::whereHas(
            'housingEstate.developer.offers',
            static fn (Builder $query) => $query->where('active', TrueFalse::True->value)
        )
            ->selectRaw('MIN(price) as minPrice, MAX(price) as maxPrice')
            ->whereNotNull('price');

        return $query->first()->toArray();
    }
}
