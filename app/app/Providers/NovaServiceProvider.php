<?php

namespace App\Providers;

use App\Nova\City;
use App\Nova\Dashboards\Main;
use App\Nova\Developer;
use App\Nova\HousingEstate;
use App\Nova\Infrastructure;
use App\Nova\Marketplace;
use App\Nova\MetroLine;
use App\Nova\MetroStation;
use App\Nova\Objects;
use App\Nova\Offer;
use App\Nova\Scenario;
use App\Nova\PaymentMethod;
use App\Nova\Promotion;
use App\Nova\Settings;
use App\Nova\Tag;
use App\Nova\Transfer;
use App\Nova\User;
use App\Nova\WorkingHours;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        Nova::style('nova', resource_path('css/nova.css'));
        Nova::script('nova', public_path('js/nova.js'));
        Nova::serving(fn () => Nova::provideToScript(['brandUrl' => config('nova.brand.url')]));

        Nova::withBreadcrumbs();
        Nova::withoutThemeSwitcher();
        Nova::withoutNotificationCenter();
        Nova::footer(fn () => '');

        Nova::mainMenu(function () {
            return [
                MenuSection::dashboard(Main::class),
                MenuSection::make(__('Developers & Housing estates'), [
                    MenuItem::resource(Developer::class),
                    MenuItem::resource(HousingEstate::class),
                    MenuItem::resource(Infrastructure::class),
                    MenuItem::resource(PaymentMethod::class),
                    MenuItem::resource(Promotion::class),
                    MenuItem::resource(Tag::class),
                ])->collapsable(),
                MenuSection::make(__('Offers'), [
                    MenuItem::resource(Offer::class),
                    MenuItem::resource(Marketplace::class),
                    MenuItem::resource(WorkingHours::class),
                    MenuItem::resource(Scenario::class),
                    MenuItem::resource(Transfer::class),
                ])->collapsable(),
                MenuSection::make(__('Objects'), [
                    MenuItem::resource(Objects::class),
                ])->collapsable(),
                MenuSection::make(__('Cities & Metro'), [
                    MenuItem::resource(City::class),
                    MenuItem::resource(MetroLine::class),
                    MenuItem::resource(MetroStation::class),
                ])->collapsable(),
                MenuSection::make(__('System'), [
                    MenuItem::resource(User::class),
                    MenuItem::resource(Settings::class),
                ])->collapsable(),
            ];
        });

        if (preg_match('=^/nova/resources/housing-estates/\d+=', request()->getRequestUri())) {
            config()->set('nova.pagination','load-more');
        }
    }

    protected function routes(): void
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    protected function gate(): void
    {
        Gate::define('viewNova', function (\App\Models\User $user) {
            return in_array($user->email, \App\Models\User::ADMIN_EMAILS) || $user->transfers_page_only;
        });
    }

    protected function dashboards(): array
    {
        return [
            new Main,
        ];
    }
}
