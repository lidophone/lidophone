<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Promotion extends ResourceCustom
{
    public static string $model = \App\Models\Promotion::class;

    public static $title = 'name';

    public static $search = ['id', 'name'];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Name'), 'name')->rules('required', 'max:255')->sortable(),
            Text::make(__('Link'), 'link')
                ->displayUsing(
                    fn (string $link) => $link ? '<a href="' . $link . '" class="link-default">' . $link . '</a>' : null
                )
                ->asHtml()
                ->rules('max:255'),
        ];
    }

    public static function label(): string
    {
        return __('Promotions');
    }

    public static function singularLabel(): string
    {
        return __('Promotion');
    }
}
