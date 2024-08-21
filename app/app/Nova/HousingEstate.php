<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Tag as TagField;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class HousingEstate extends ResourceCustom
{
    public static string $model = \App\Models\HousingEstate::class;

    public static $title = 'name';

    public static $search = ['id', 'name'];

    public function fields(NovaRequest $request): array
    {
        /** @var $model \App\Models\HousingEstate */
        $model = $this->model();
        $readonly = $model->developer && $model->developer->automatic_handling;
        return [
            ID::make()->sortable(),
            Text::make(__('Name'), 'name')
                ->rules('required', 'max:255')
                ->readonly($readonly)
                ->sortable(),
            BelongsTo::make(__('Developer'), 'developer', Developer::class)->readonly($readonly),
            BelongsTo::make(__('City'), 'city', City::class)->readonly($readonly),
            Boolean::make(__('Region'), 'is_region')->readonly($readonly),
            Text::make(__('Latitude'), 'latitude')->rules('required', 'max:255'),
            Text::make(__('Longitude'), 'longitude')->rules('required', 'max:255'),
            Text::make(__('Site'), 'site')
                ->displayUsing(
                    fn (?string $site) => $site
                        ? '<a href="' . $site . '" class="link-default">' . $site . '</a>'
                        : null
                )
                ->asHtml()
                ->rules('max:255')
                ->readonly($readonly),
            Images::make(__('Photos'), 'images')->hideWhenUpdating($readonly),

            BelongsToMany::make(__('Infrastructure'), 'infrastructure', Infrastructure::class)
                ->fields(fn () => array_merge([
                    Text::make(__('Name'), 'name')->rules('required', 'max:255')->sortable(),
                ], $this->timeOnFootByCarFields())),
            BelongsToMany::make(__('Metro stations'), 'metroStations', MetroStation::class)
                ->fields(fn () => $this->timeOnFootByCarFields())
                ->display(function (MetroStation $metroStation) {
                    /** @var $resource \App\Models\MetroStation */
                    $resource = $metroStation->resource;
                    return $resource->name . ' (' . $resource->metroLine->name . ')';
                }),
            BelongsToMany::make(__('Payment methods'), 'paymentMethods', PaymentMethod::class),
            BelongsToMany::make(__('Promotions'), 'promotions', Promotion::class),

            HasMany::make(__('Objects'), 'objects', Objects::class),
            HasMany::make(__('Offers'), 'offers', Offer::class),
            TagField::make(__('Tags'), 'tags', Tag::class),

            Textarea::make(__('Location'), 'location')->rules('required'),
            Textarea::make(__('Advantages'), 'advantages')->rules('required'),
            Textarea::make(__('Payment'), 'payment'),
        ];
    }

    public function authorizedToDelete(Request $request): bool
    {
        /** @var $model \App\Models\HousingEstate */
        $model = $this->model();
        return !$model->developer->automatic_handling;
    }

    public static function label(): string
    {
        return __('Housing estates');
    }

    public static function singularLabel(): string
    {
        return __('Housing estate');
    }

    private function timeOnFootByCarFields(): array
    {
        return [
            Text::make(__('Time on foot'), 'time_on_foot')
                ->rules('required_without:time_by_car', 'nullable', 'integer'),
            Text::make(__('Time by car'), 'time_by_car')
                ->rules('required_without:time_on_foot', 'nullable', 'integer'),
        ];
    }
}
