<?php

namespace App\Nova;

use App\Helpers\MetroLineHelper;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class MetroLine extends ResourceCustom
{
    public static string $model = \App\Models\MetroLine::class;

    public static $title = 'name';

    public static $search = ['id', 'name'];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make(__('City'), 'city', City::class)
                ->sortable()
                ->withMeta(['sortableUriKey' => 'city_name']),
            Text::make(__('Name'), 'name')->rules('required', 'max:255')->sortable(),
            Text::make(__('Color'), 'color')
                ->displayUsing(fn (string $color) => MetroLineHelper::getColorBox($color))
                ->asHtml()
                ->rules('required', 'min:7', 'max:7'),
            Text::make(__('Designation'), 'designation')->rules('max:2'),
        ];
    }

    /**
     * @see https://github.com/laravel/nova-issues/issues/178#issuecomment-598591625
     */
    public static function indexQuery(NovaRequest $request, $query): Builder
    {
        return $query
            ->join('cities', 'metro_lines.city_id', '=', 'cities.id')
            ->select('metro_lines.*', 'cities.name as city_name');
    }

    public static function label(): string
    {
        return __('Metro lines');
    }

    public static function singularLabel(): string
    {
        return __('Metro line');
    }
}
