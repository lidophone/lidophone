<?php

namespace App\Nova\Filters;

use App\Enums\TrueFalse;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaMultiselectFilter\MultiselectFilter;

class ActiveInactive extends MultiselectFilter
{
    public function name(): string
    {
        return __('Status');
    }

    public function apply(NovaRequest $request, $query, $value): Builder
    {
        return $query->whereIn('active', $value);
    }

    public function options(NovaRequest $request): array
    {
        return [
            TrueFalse::True->value => __('Active'),
            TrueFalse::False->value => __('Inactive'),
        ];
    }
}
