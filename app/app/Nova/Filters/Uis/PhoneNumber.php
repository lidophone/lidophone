<?php

namespace App\Nova\Filters\Uis;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Fields\Filters\TextFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class PhoneNumber extends TextFilter
{
    public function name(): string
    {
        return __('Phone number');
    }

    public function apply(NovaRequest $request, $query, $value): Builder
    {
        return $query->where('uis_calls.contact_phone_number', 'like', "%$value%");
    }
}
