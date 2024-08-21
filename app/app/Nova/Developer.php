<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Developer extends ResourceCustom
{
    public static string $model = \App\Models\Developer::class;

    public static $title = 'name';

    public static $search = ['id', 'name'];

    public function fields(NovaRequest $request): array
    {
        /** @var $model \App\Models\Developer */
        $model = $this->model();
        $readonly = (bool) $model->automatic_handling;
        return [
            ID::make()->sortable(),
            Text::make(__('Name'), 'name')
                ->rules('required', 'max:255')
                ->creationRules('unique:developers')
                ->readonly($readonly)
                ->sortable(),
            BelongsToMany::make(__('Payment methods'), 'paymentMethods', PaymentMethod::class),
        ];
    }

    public function authorizedToUpdate(Request $request): bool
    {
        /** @var $model \App\Models\Developer */
        $model = $this->model();
        return !$model->automatic_handling;
    }

    public function authorizedToDelete(Request $request): bool
    {
        /** @var $model \App\Models\Developer */
        $model = $this->model();
        return !$model->automatic_handling;
    }

    public static function label(): string
    {
        return __('Developers');
    }

    public static function singularLabel(): string
    {
        return __('Developer');
    }
}
