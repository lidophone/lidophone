<?php

namespace App\Nova;

use App\Enums\DayOfWeek;
use App\Rules\OnePriorityOfferForHousingEstate;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Trin4ik\NovaSwitcher\NovaSwitcher;

class Offer extends ResourceCustom
{
    public static string $model = \App\Models\Offer::class;

    public static $title = 'name';

    public static $search = ['id', 'name', 'sip_uri'];

    public static $perPageOptions = [100, 50, 25];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            NovaSwitcher::make(__('Active'), 'active')->sortable(),
            Boolean::make(__('Priority'), 'priority')
                ->sortable()
                ->rules([new OnePriorityOfferForHousingEstate($this->model())]),
            Boolean::make(__('Expert mode'), 'expert_mode')->filterable(),
            Boolean::make(__('Other developers'), 'other_developers'),
            BelongsTo::make(__('Marketplace'), 'marketplace', Marketplace::class),
            BelongsTo::make(__('Developer'), 'developer', Developer::class),
            Select::make(__('Housing estate'), 'housing_estate_id')
                ->dependsOn('developer', function (Select $field, NovaRequest $request, FormData $formData) {
                    if ($formData->developer) { // ID
                        $field
                            ->options(\App\Models\HousingEstate::getSelectOptions($formData->developer))
                            ->setValue($formData->housing_estate_id);
                    }
                })
                ->onlyOnForms()
            ,
            BelongsTo::make(__('Housing estate'), 'housingEstate', HousingEstate::class)
                ->hideWhenCreating()
                ->hideWhenUpdating()
            ,
            Text::make(__('Name'), 'name')->rules('required')->sortable(),
            BelongsTo::make(__('Scenario'), 'scenario', Scenario::class)
                ->displayUsing(function (Scenario $scenario) {
                    /** @var $model \App\Models\Scenario */
                    $model = $scenario->model();
                    return $model->name ? $model->name : $model->id;
                })
                ->rules('required'),
            Text::make(__('Price'), 'price')->rules('required', 'integer'),
            Text::make(__('Operator award'), 'operator_award')
                ->rules('required', 'numeric', 'lt:1')
                ->textAlign('center'),
            Text::make(__('SIP URI'), 'sip_uri')->rules('required', 'max:255'),
            Text::make(__('Working hours'), 'workingHours')
                ->displayUsing(function (Collection $workingHours) {
                    /** @var \App\Models\WorkingHours[] $workingHours */
                    $result = '';
                    foreach ($workingHours as $workingHoursInterval) {
                        $result .= DayOfWeek::getName($workingHoursInterval->day_of_week) . ': ' .
                            $workingHoursInterval->start_time . '-' . $workingHoursInterval->end_time . '<br>';
                    }
                    return $result;
                })
                ->asHtml()
                ->onlyOnIndex(),
            HasMany::make(__('Working hours'), 'workingHours', WorkingHours::class),
            Text::make(__('Uniqueness period'), 'uniqueness_period')
                ->rules('required', 'integer')
                ->textAlign('center'),
            Boolean::make(__('Looking not for himself'), 'looking_not_for_himself'),
            Boolean::make(__('Client is out of town'), 'client_is_out_of_town'),
            Text::make(__('External ID'), 'external_id')->rules('required', 'integer'),
            Text::make(__('Call limit'), 'call_limit')->rules('required', 'integer'),
        ];
    }

    public function filters(NovaRequest $request): array
    {
        return [
            new Filters\ActiveInactive,
            new Filters\Marketplace,
            new Filters\Developer,
            new Filters\HousingEstate,
        ];
    }

    public static function label(): string
    {
        return __('Offers');
    }

    public static function singularLabel(): string
    {
        return __('Offer');
    }
}
