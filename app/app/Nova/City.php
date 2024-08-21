<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class City extends ResourceCustom
{
    public static string $model = \App\Models\City::class;

    public static $title = 'name';

    public static $search = ['id', 'name'];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Name'), 'name')
                ->rules('required', 'max:255')
                ->creationRules('unique:cities')
                ->sortable(),
            Text::make(__('Latitude'), 'latitude')->rules('required', 'decimal:0,6'),
            Text::make(__('Longitude'), 'longitude')->rules('required', 'decimal:0,6'),
            Text::make(__('Region name'), 'region_name')->rules('max:255'),
        ];
    }

    public static function label(): string
    {
        return __('Cities');
    }

    public static function singularLabel(): string
    {
        return __('City');
    }
}
