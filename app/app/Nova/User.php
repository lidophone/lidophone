<?php

namespace App\Nova;

use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends ResourceCustom
{
    public static string $model = \App\Models\User::class;

    public static $title = 'name';

    public static $search = ['id', 'name', 'email'];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Gravatar::make(__('Avatar'))->maxWidth(50),
            Text::make(__('Name'), 'name')->rules('required', 'max:255'),
            Text::make('Email')
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),
            Password::make(__('Password'), 'password')
                ->onlyOnForms()
                ->creationRules('required', Rules\Password::defaults())
                ->updateRules('nullable', Rules\Password::defaults()),
            Boolean::make(__('Expert mode'), 'expert_mode'),
            Boolean::make(__('"Transfers" page only'), 'transfers_page_only'),
        ];
    }

    public static function label(): string
    {
        return __('Users');
    }

    public static function singularLabel(): string
    {
        return __('User');
    }
}
