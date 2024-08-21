<?php

namespace App\Models\Uis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Uis\UisEmployeeGroup
 *
 * @property int $uis_employees_uis_id
 * @property int $uis_groups_uis_id
 * @method static \Illuminate\Database\Eloquent\Builder|UisEmployeeGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UisEmployeeGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UisEmployeeGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|UisEmployeeGroup whereUisEmployeesUisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisEmployeeGroup whereUisGroupsUisId($value)
 * @mixin \Eloquent
 */
class UisEmployeeGroup extends Model
{
    use HasFactory;

    protected $table = 'uis_employees_groups';
}
