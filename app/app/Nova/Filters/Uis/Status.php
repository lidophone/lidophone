<?php

namespace App\Nova\Filters\Uis;

use App\Enums\Uis\UisStatus;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaMultiselectFilter\MultiselectFilter;

class Status extends MultiselectFilter
{
    public function name(): string
    {
        return __('Status');
    }

    public function apply(NovaRequest $request, $query, $value): Builder
    {
        return $query->whereIn('uis_calls.status', $value);
    }

    public function options(NovaRequest $request): array
    {
        return UisStatus::getSelectOptions();
    }
}
