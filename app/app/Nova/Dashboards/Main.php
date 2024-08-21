<?php

namespace App\Nova\Dashboards;

use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    public function cards(): array
    {
        return [];
    }

    public function label(): string
    {
        return __('Main');
    }
}
