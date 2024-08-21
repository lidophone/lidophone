<?php

namespace Database\Seeders;

use App\Models\Scenario;
use Illuminate\Database\Seeder;

class ScenariosSeeder extends Seeder
{
    public function run(): void
    {
        $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
        Scenario::insert([
            ['name' => 'Скрипт 1', 'scenario' => '<b>1.</b> ' . $lorem],
            ['name' => 'Скрипт 2', 'scenario' => '<b>2.</b> ' . $lorem],
            ['name' => 'Скрипт 3', 'scenario' => '<b>3.</b> ' . $lorem],
        ]);
    }
}
