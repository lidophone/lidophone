<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Str;

class Scenario extends ResourceCustom
{
    public static string $model = \App\Models\Scenario::class;

    public static $title = 'name';

    public static $search = ['id', 'name', 'scenario'];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Name'), 'name')->rules('required', 'max:255'),
            Text::make(__('Scenario'), 'scenario')
                ->displayUsing(fn ($scenario) => Str::limit($scenario))
                ->onlyOnIndex(),
            Textarea::make(__('Scenario'), 'scenario')
                ->rows(10)
                ->alwaysShow()
                ->rules('required'),
        ];
    }

    public static function label(): string
    {
        return __('Scenarios');
    }

    public static function singularLabel(): string
    {
        return __('Scenario');
    }
}
