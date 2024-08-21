<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        switch ($this->app->getLocale()) {
            case 'ru':
                ${'local-LOCALE'} = 'ru-RU';
                $local_LOCALE = 'ru_RU';
                break;
            default:
                ${'local-LOCALE'} = 'en-EN';
                $local_LOCALE = 'en_EN';
        }
        $settings = [
            'locale-LOCALE' => ${'local-LOCALE'},
            'locale_LOCALE' => $local_LOCALE,
        ];
        config()->set('settings', $settings);
    }
}
