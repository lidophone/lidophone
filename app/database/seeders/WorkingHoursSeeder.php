<?php

namespace Database\Seeders;

use App\Enums\DayOfWeek;
use App\Models\WorkingHours;
use Illuminate\Database\Seeder;

class WorkingHoursSeeder extends Seeder
{
    public function run(): void
    {
        WorkingHours::insert([
            ['day_of_week' => DayOfWeek::MoFr->value, 'offer_id' => 1, 'start_time' => '09:00', 'end_time' => '23:00'],
            ['day_of_week' => DayOfWeek::MoFr->value, 'offer_id' => 1, 'start_time' => '09:00', 'end_time' => '23:00'],
            ['day_of_week' => DayOfWeek::MoFr->value, 'offer_id' => 2, 'start_time' => '09:00', 'end_time' => '23:00'],
            ['day_of_week' => DayOfWeek::MoFr->value, 'offer_id' => 2, 'start_time' => '09:00', 'end_time' => '23:00'],
            ['day_of_week' => DayOfWeek::MoFr->value, 'offer_id' => 3, 'start_time' => '09:00', 'end_time' => '23:00'],
            ['day_of_week' => DayOfWeek::MoFr->value, 'offer_id' => 3, 'start_time' => '09:00', 'end_time' => '23:00'],
            ['day_of_week' => DayOfWeek::MoFr->value, 'offer_id' => 4, 'start_time' => '09:00', 'end_time' => '23:00'],
            ['day_of_week' => DayOfWeek::MoFr->value, 'offer_id' => 4, 'start_time' => '09:00', 'end_time' => '23:00'],
            ['day_of_week' => DayOfWeek::MoFr->value, 'offer_id' => 5, 'start_time' => '09:00', 'end_time' => '23:00'],
            ['day_of_week' => DayOfWeek::MoFr->value, 'offer_id' => 5, 'start_time' => '09:00', 'end_time' => '23:00'],
            ['day_of_week' => DayOfWeek::MoFr->value, 'offer_id' => 6, 'start_time' => '09:00', 'end_time' => '23:00'],
            ['day_of_week' => DayOfWeek::MoFr->value, 'offer_id' => 6, 'start_time' => '09:00', 'end_time' => '23:00'],
            ['day_of_week' => DayOfWeek::MoFr->value, 'offer_id' => 7, 'start_time' => '09:00', 'end_time' => '23:00'],
            ['day_of_week' => DayOfWeek::MoFr->value, 'offer_id' => 7, 'start_time' => '09:00', 'end_time' => '23:00'],
        ]);
    }
}
