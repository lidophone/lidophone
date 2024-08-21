<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Scenario
 *
 * @property int $id
 * @property string $name
 * @property string $scenario
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Scenario newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Scenario newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Scenario onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Scenario query()
 * @method static \Illuminate\Database\Eloquent\Builder|Scenario whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scenario whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scenario whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scenario whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scenario whereScenario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scenario whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scenario withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Scenario withoutTrashed()
 * @mixin \Eloquent
 */
class Scenario extends Model
{
    use HasFactory;
    use SoftDeletes;
}
