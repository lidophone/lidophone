<?php

namespace App\Nova;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Marketplace extends ResourceCustom
{
    public static string $model = \App\Models\Marketplace::class;

    public static $title = 'name';

    public static $search = ['id', 'name'];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Name'), 'name')->rules('required', 'max:255'),
            Boolean::make(__('Expert mode'), 'expert_mode'),
            Boolean::make(__('Other developers'), 'other_developers'),
        ];
    }

    public static function label(): string
    {
        return __('Marketplaces');
    }

    public static function singularLabel(): string
    {
        return __('Marketplace');
    }
}
