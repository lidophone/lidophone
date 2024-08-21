<?php

namespace App\Providers;

use App;
use App\Models\Settings;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Support\ServiceProvider;
use Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        define('YANDEX_MAPS_API_KEY', config('app.yandex.maps.' . (App::isLocal() ? 'apiKeyLocal' : 'apiKey')));
        define('LIDOFON_DATE_TIME_UTC', new DateTimeImmutable());
        define('LIDOFON_DATE_TIME_MOSCOW', new DateTimeImmutable('now', new DateTimeZone('Europe/Moscow')));
        if (Schema::hasTable('settings')) { // To run the project locally
            if (App::environment('local')) {
                config()->set('services.telegram-bot-api.token', '6742704391:AAGkwI07ReCB5MAlmz3q9zUNEjNE3SknHJA');
            } else {
                config()->set('services.telegram-bot-api.token', Settings::where('key', 'tg_bot_token')->value('value'));
            }
        }
    }
}
