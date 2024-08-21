<?php

namespace App\Models\Uis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Uis\UisGroup
 *
 * @property int $id
 * @property int $uis_id
 * @property string $name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UisGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UisGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UisGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|UisGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisGroup whereUisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisGroup whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UisGroup extends Model
{
    use HasFactory;
}
