<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Infrastructure extends ResourceCustom
{
    public static string $model = \App\Models\Infrastructure::class;

    public static $title = 'designation';

    public static $search = ['id', 'designation'];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Designation'), 'designation')->rules('required', 'max:255')->sortable(),
            Image::make(__('Icon'), 'icon_filename')->prunable(),
        ];
    }

    public static function label(): string
    {
        return __('Infrastructure');
    }
}
