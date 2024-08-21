<?php

namespace App\Nova\Filters\Uis;

use App\Helpers\UisHelper;
use App\Models\Uis\UisEmployee;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaMultiselectFilter\MultiselectFilter;

class Employee extends MultiselectFilter
{
    public function name(): string
    {
        return __('Employee');
    }

    public function apply(NovaRequest $request, $query, $value): Builder
    {
        return $query->whereIn('uis_calls.first_answered_employee_id', $value);
    }

    public function options(NovaRequest $request): array
    {
        return UisEmployee::withWhereHas(
            'groups',
            fn (Builder $query) => $query->whereNot('uis_id', UisHelper::DEVELOPERS_GROUP_ID)
        )
            ->orderBy('full_name')
            ->pluck('full_name', 'uis_id')
            ->toArray();
    }
}
