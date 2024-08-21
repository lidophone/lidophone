<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Settings extends ResourceCustom
{
    public static string $model = \App\Models\Settings::class;

    public static $title = 'name';

    public static $search = ['id', 'name', 'key', 'value'];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Name'), 'name')->rules('required', 'max:255')->sortable(),
            Text::make(__('Key'), 'key')->rules('required', 'max:255')->sortable(),
            Text::make(__('Value'), 'value')->rules('required', 'max:255')->sortable(),
        ];
    }

    public static function label(): string
    {
        return __('Settings');
    }

    public static function singularLabel(): string
    {
        return __('Settings');
    }
}
