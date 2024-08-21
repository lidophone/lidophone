<?php

namespace App\Nova;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class MetroStation extends ResourceCustom
{
    public static string $model = \App\Models\MetroStation::class;

    public static $title = 'name';

    public static $search = ['id', 'name'];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make(__('City'), 'city', City::class)
                ->sortable()
                ->withMeta(['sortableUriKey' => 'city_name'])
                ->hideWhenCreating()
                ->hideWhenUpdating(),
            BelongsTo::make(__('Metro line'), 'metroLine', MetroLine::class)
                ->sortable()
                ->withMeta(['sortableUriKey' => 'metro_line_name'])
                ->hideWhenCreating()
                ->hideWhenUpdating(),
            Select::make( __('City'),  'city',
                function (?\App\Models\City $city,  \App\Models\MetroStation $metroStation) {
                    if ($metroStation->city) {
                        return $metroStation->city->id;
                    }
                    return null;
                }
            )
                ->options(\App\Models\City::getSelectOptions())
                ->fillUsing(fn () => null) // https://github.com/laravel/nova-issues/issues/2334#issuecomment-583352864
                ->rules('required')
                ->onlyOnForms(),
            Select::make(__('Metro line'), 'metro_line_id')
                ->rules('required')
                ->dependsOn('city', function (Select $field, NovaRequest $request, FormData $formData) {
                    if ($formData->city) { // ID
                        $field->options(\App\Models\MetroLine::getSelectOptions($formData->city));
                    }
                })
                ->onlyOnForms(),
            Text::make(__('Name'), 'name')->rules('required', 'max:255')->sortable(),
            Boolean::make(__('Under construction'), 'under_construction'),
            Text::make(__('Latitude'), 'latitude')
                ->dependsOn('under_construction', $this->showHideCoordsFields())
                ->onlyOnForms(),
            Text::make(__('Longitude'), 'longitude')
                ->dependsOn('under_construction', $this->showHideCoordsFields())
                ->onlyOnForms(),
        ];
    }

    /**
     * @see https://github.com/laravel/nova-issues/issues/178#issuecomment-598591625
     */
    public static function indexQuery(NovaRequest $request, $query): Builder
    {
        return $query
            ->join('metro_lines', 'metro_stations.metro_line_id', '=', 'metro_lines.id')
            ->join('cities', 'metro_lines.city_id', '=', 'cities.id')
            ->select('metro_stations.*', 'metro_lines.name as metro_line_name', 'cities.name as city_name');
    }

    public static function label(): string
    {
        return __('Metro stations');
    }

    public static function singularLabel(): string
    {
        return __('Metro station');
    }

    private function showHideCoordsFields(): Closure
    {
        return function (Text $field, NovaRequest $request, FormData $formData) {
            if (!$formData->under_construction) {
                $field->hide()
                    ->setValue('');
            }
        };
    }
}
