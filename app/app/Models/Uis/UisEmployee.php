<?php

namespace App\Models\Uis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * App\Models\Uis\UisEmployee
 *
 * @property int $id
 * @property int $uis_id
 * @property string $full_name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Uis\UisGroup> $groups
 * @property-read int|null $groups_count
 * @method static \Illuminate\Database\Eloquent\Builder|UisEmployee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UisEmployee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UisEmployee query()
 * @method static \Illuminate\Database\Eloquent\Builder|UisEmployee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisEmployee whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisEmployee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisEmployee whereUisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisEmployee whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UisEmployee extends Model
{
    use HasFactory;

    public function groups(): HasManyThrough
    {
        return $this->hasManyThrough(
            UisGroup::class,
            UisEmployeeGroup::class,
            'uis_employees_uis_id',
            'uis_id',
            'uis_id',
            'uis_groups_uis_id',
        );
    }
}
