<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class PaymentMethod extends ResourceCustom
{
    public static string $model = \App\Models\PaymentMethod::class;

    public static $title = 'name';

    public static $search = ['id', 'name'];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Name'), 'name')->rules('required', 'max:255')->sortable(),
        ];
    }

    public static function label(): string
    {
        return __('Payment methods');
    }

    public static function singularLabel(): string
    {
        return __('Payment method');
    }
}
