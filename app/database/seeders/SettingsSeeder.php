<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Settings::insert([
            ['name' => 'Парсить звонки UIS', 'key' => 'parse_uis_calls', 'value' => 1],
            ['name' => 'Ежедневный рейтинг — Менеджер', 'key' => 'company_record_manager', 'value' => 'Илья'],
            ['name' => 'Ежедневный рейтинг — Дата', 'key' => 'company_record_date', 'value' => '01.01.2023'],
            ['name' => 'Ежедневный рейтинг — Количество переводов', 'key' => 'company_record_number_of_transfers', 'value' => '43'],
            ['name' => 'Ежедневный рейтинг — Моментальная выплата', 'key' => 'company_record_instant_payment', 'value' => '10 000'],
        ]);
    }
}
