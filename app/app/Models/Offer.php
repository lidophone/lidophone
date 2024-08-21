<?php

namespace App\Models;

use App\Models\Uis\UisCall;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Offer
 *
 * @property int $id
 * @property int $developer_id
 * @property int|null $housing_estate_id
 * @property int|null $marketplace_id
 * @property string $name
 * @property int $external_id
 * @property string $sip_uri
 * @property int $call_limit
 * @property int $uniqueness_period Срок уникальности (https://shorturl.at/nzHJ5)
 * @property int $price
 * @property float $operator_award
 * @property int $client_is_out_of_town
 * @property int $looking_not_for_himself
 * @property int $scenario_id
 * @property int $expert_mode
 * @property int $other_developers
 * @property int $priority
 * @property int $active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Developer $developer
 * @property-read \App\Models\HousingEstate|null $housingEstate
 * @property-read \App\Models\Marketplace|null $marketplace
 * @property-read \App\Models\Scenario $scenario
 * @property-read \Illuminate\Database\Eloquent\Collection<int, UisCall> $uisCalls
 * @property-read int|null $uis_calls_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WorkingHours> $workingHours
 * @property-read int|null $working_hours_count
 * @method static \Illuminate\Database\Eloquent\Builder|Offer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereCallLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereClientIsOutOfTown($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereDeveloperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereExpertMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereHousingEstateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereLookingNotForHimself($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereMarketplaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereOperatorAward($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereOtherDevelopers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereScenarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereSipUri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereUniquenessPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer withoutTrashed()
 * @mixin \Eloquent
 */
class Offer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'developer_id',
        'active',
    ];

    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class);
    }

    public function marketplace(): BelongsTo
    {
        return $this->belongsTo(Marketplace::class);
    }

    public function housingEstate(): BelongsTo
    {
        return $this->belongsTo(HousingEstate::class);
    }

    public function workingHours(): HasMany
    {
        return $this->hasMany(WorkingHours::class);
    }

    public function scenario(): BelongsTo
    {
        return $this->belongsTo(Scenario::class);
    }

    public function uisCalls(): HasMany
    {
        return $this->hasMany(UisCall::class, 'developer_id', 'developer_id');
    }
}
