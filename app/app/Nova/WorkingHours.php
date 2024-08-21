<?php

namespace App\Nova;

use App\Enums\DayOfWeek;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class WorkingHours extends ResourceCustom
{
    public static string $model = \App\Models\WorkingHours::class;

    public static $title = 'offer.name';

    public function fields(NovaRequest $request): array
    {
        return [
            BelongsTo::make(__('Offer'), 'offer', Offer::class),
            Select::make(__('Day(s) of week'), 'day_of_week')
                ->displayUsing(fn (int $dayOfWeek) => DayOfWeek::getName($dayOfWeek))
                ->options(DayOfWeek::getSelectOptions())
                ->rules('required'),
            Text::make(__('Start time'), 'start_time')->rules('required', 'max:255'),
            Text::make(__('End time'), 'end_time')->rules('required', 'max:255'),
        ];
    }

    public static function label(): string
    {
        return __('Working hours');
    }
}
