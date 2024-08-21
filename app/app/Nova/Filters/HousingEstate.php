<?php

namespace App\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaMultiselectFilter\MultiselectFilter;

class HousingEstate extends MultiselectFilter
{
    public function name(): string
    {
        return __('Housing estate');
    }

    public function apply(NovaRequest $request, $query, $value): Builder
    {
        return $query->whereIn('housing_estate_id', $value);
    }

    public function options(NovaRequest $request): array
    {
        return \App\Models\HousingEstate::orderBy('name')->pluck('name', 'id')->toArray();
    }
}
