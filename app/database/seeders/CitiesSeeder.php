<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitiesSeeder extends Seeder
{
    public function run(): void
    {
        City::insert([
            [
                'name' => 'Москва',
                'latitude' => 55.755864,
                'longitude' => 37.617698,
                'region_name' => 'Московская область',
            ],
            [
                'name' => 'Санкт-Петербург',
                'latitude' => 59.938955,
                'longitude' => 30.315644,
                'region_name' => 'Ленинградская область',
            ],
            [
                'name' => 'Новосибирск',
                'latitude' => 0,
                'longitude' => 0,
                'region_name' => null,
            ],
        ]);
    }
}
