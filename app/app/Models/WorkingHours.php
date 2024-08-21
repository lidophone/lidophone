<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\WorkingHours
 *
 * @property int $id
 * @property int $offer_id
 * @property int $day_of_week
 * @property string $start_time
 * @property string $end_time
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Offer $offer
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHours newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHours newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHours onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHours query()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHours whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHours whereDayOfWeek($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHours whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHours whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHours whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHours whereOfferId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHours whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHours whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHours withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHours withoutTrashed()
 * @mixin \Eloquent
 */
class WorkingHours extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }
}
