<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\HousingEstate
 *
 * @property int $id
 * @property string $name
 * @property int $developer_id
 * @property int $city_id
 * @property int $is_region
 * @property string $latitude
 * @property string $longitude
 * @property string|null $site
 * @property string $location
 * @property string $advantages
 * @property string|null $payment
 * @property mixed|null $images
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\City $city
 * @property-read \App\Models\Developer $developer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Infrastructure> $infrastructure
 * @property-read int|null $infrastructure_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MetroStation> $metroStations
 * @property-read int|null $metro_stations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Objects> $objects
 * @property-read int|null $objects_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Offer> $offers
 * @property-read int|null $offers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PaymentMethod> $paymentMethods
 * @property-read int|null $payment_methods_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Promotion> $promotions
 * @property-read int|null $promotions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @method static Builder|HousingEstate newModelQuery()
 * @method static Builder|HousingEstate newQuery()
 * @method static Builder|HousingEstate onlyTrashed()
 * @method static Builder|HousingEstate query()
 * @method static Builder|HousingEstate whereAdvantages($value)
 * @method static Builder|HousingEstate whereCityId($value)
 * @method static Builder|HousingEstate whereCreatedAt($value)
 * @method static Builder|HousingEstate whereDeletedAt($value)
 * @method static Builder|HousingEstate whereDeveloperId($value)
 * @method static Builder|HousingEstate whereId($value)
 * @method static Builder|HousingEstate whereImages($value)
 * @method static Builder|HousingEstate whereIsRegion($value)
 * @method static Builder|HousingEstate whereLatitude($value)
 * @method static Builder|HousingEstate whereLocation($value)
 * @method static Builder|HousingEstate whereLongitude($value)
 * @method static Builder|HousingEstate whereName($value)
 * @method static Builder|HousingEstate wherePayment($value)
 * @method static Builder|HousingEstate whereSite($value)
 * @method static Builder|HousingEstate whereUpdatedAt($value)
 * @method static Builder|HousingEstate withTrashed()
 * @method static Builder|HousingEstate withoutTrashed()
 * @mixin \Eloquent
 */
class HousingEstate extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'developer_id',
        'city_id',
        'is_region',
        'latitude',
        'longitude',
        'site',
        'location',
        'advantages',
        'advantages',
        'images',
    ];

    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class);
    }

    public function city(): BelongsTo
    {
        return $this->BelongsTo(City::class);
    }

    public function infrastructure(): BelongsToMany
    {
        return $this->belongsToMany(Infrastructure::class, HousingEstateInfrastructure::TABLE)
            ->withPivot('name', 'time_on_foot', 'time_by_car');
    }

    public function metroStations(): BelongsToMany
    {
        return $this->belongsToMany(MetroStation::class, HousingEstateMetroStation::TABLE)
            ->withPivot('time_on_foot', 'time_by_car');
    }

    public function paymentMethods(): BelongsToMany
    {
        return $this->belongsToMany(PaymentMethod::class, HousingEstatePaymentMethod::TABLE);
    }

    public function promotions(): BelongsToMany
    {
        return $this->belongsToMany(Promotion::class, HousingEstatePromotion::TABLE);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, HousingEstateTag::TABLE);
    }

    public function objects(): HasMany
    {
        return $this->hasMany(Objects::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    public static function getSelectOptions(int $developerId = null): array
    {
        $query = self::select(['id', 'name']);
        if ($developerId) {
            $query->where('developer_id', $developerId);
        }
        return $query->orderBy('name')->pluck('name', 'id')->toArray();
    }
}
