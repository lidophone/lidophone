<?php

namespace App\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaMultiselectFilter\MultiselectFilter;

class Marketplace extends MultiselectFilter
{
    public function name(): string
    {
        return __('Marketplace');
    }

    public function apply(NovaRequest $request, $query, $value): Builder
    {
        return $query->whereIn('marketplace_id', $value);
    }

    public function options(NovaRequest $request): array
    {
        return \App\Models\Marketplace::orderBy('name')->pluck('name', 'id')->toArray();
    }
}
