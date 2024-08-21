<?php

namespace App\Nova;

use App\Enums\Deadline;
use App\Enums\Finishing;
use App\Enums\RealEstateType;
use App\Enums\Roominess;
use Closure;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaInlineTextField\InlineText;

class Objects extends ResourceCustom // Don't rename to "Object" because it's a reserved word
{
    public static string $model = \App\Models\Objects::class;

    public static $title = 'id';

    public static $search = ['id', 'housingEstate.name'];

    public static array $orderBy = [
        'done' => 'desc',
        'deadline_year' => 'asc',
        'deadline_quarter' => 'asc',
        'real_estate_type' => 'asc',
        'finishing' => 'asc',
        'roominess' => 'asc',
    ];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make(__('Housing estate'), 'housingEstate', HousingEstate::class),
            Select::make(__('Roominess'), 'roominess')
                ->displayUsing(fn (int $value) => Roominess::getName($value))
                ->options(Roominess::getSelectOptions())
                ->rules('required'),
            Select::make(__('Type'), 'real_estate_type')
                ->displayUsing(fn (int $value) => RealEstateType::getName($value))
                ->options(RealEstateType::getSelectOptions())
                ->rules('required'),
            Select::make(__('Finishing'), 'finishing')
                ->displayUsing(fn (int $value) => Finishing::getName($value))
                ->options(Finishing::getSelectOptions())
                ->rules('required'),
            InlineText::make(__('Square meters'), 'square_meters')
                ->rules('required')
                ->onlyOnIndex(),
            Text::make(__('Square meters'), 'square_meters')
                ->rules('required')
                ->hideFromIndex(),
            Text::make(__('Price per m²'))
                ->displayUsing(fn (null $null, \App\Models\Objects $object) =>
                    // https://stackoverflow.com/a/2901298/4223982
                    preg_replace('~\B(?=(\d{3})+(?!\d))~', ' ', round($object->price / $object->square_meters))
                )
                ->showOnIndex()
                ->showOnDetail()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
            InlineText::make(__('Price'), 'price')
                ->rules('required')
                ->onlyOnIndex(),
            Text::make(__('Price'), 'price')
                ->rules('required')
                ->hideFromIndex(),
            Text::make(__('Deadline'))
                ->displayUsing(function (null $deadline, \App\Models\Objects $object) {
                    if ($object->deadline_quarter && $object->deadline_year) {
                        return $object->deadline_quarter . ' ' . __('qr.') . ' ' . $object->deadline_year;
                    } elseif ($object->done) {
                        return  __('Done');
                    } else {
                        return '—';
                    }
                })
                ->showOnIndex()
                ->showOnDetail()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
            Heading::make(__('Deadline'))->onlyOnForms(),
            Boolean::make(__('Done'), 'done')
                ->hideFromIndex()
                ->hideFromDetail(),
            Select::make(__('Year'), 'deadline_year')
                ->options(Deadline::getYearSelectOption())
                ->dependsOn('done', $this->showHideDeadlineFields())
                ->onlyOnForms(),
            Select::make(__('Quarter'), 'deadline_quarter')
                ->options(Deadline::getQuarterSelectOption())
                ->dependsOn('done', $this->showHideDeadlineFields())
                ->onlyOnForms(),
        ];
    }

    public static function label(): string
    {
        return __('Objects');
    }

    public static function singularLabel(): string
    {
        return __('Object');
    }

    private function showHideDeadlineFields(): Closure
    {
        return function (Select $field, NovaRequest $request, FormData $formData) {
            if ($formData->done) {
                $field->options([])
                    ->hide();
            }
        };
    }
}
