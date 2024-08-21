<?php

namespace App\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaMultiselectFilter\MultiselectFilter;

class Developer extends MultiselectFilter
{
    public function name(): string
    {
        return __('Developer');
    }

    public function apply(NovaRequest $request, $query, $value): Builder
    {
        return $query->whereIn('developer_id', $value);
    }

    public function options(NovaRequest $request): array
    {
        return \App\Models\Developer::orderBy('name')->pluck('name', 'id')->toArray();
    }
}
