<?php

namespace Database\Seeders;

use App\Models\HousingEstateMetroStation;
use Illuminate\Database\Seeder;

class HousingEstatesMetroStationsSeeder extends Seeder
{
    public function run(): void
    {
        HousingEstateMetroStation::insert([
            ['housing_estate_id' => 1, 'metro_station_id' => 1, 'time_by_car' => 1, 'time_on_foot' => 2],
            ['housing_estate_id' => 1, 'metro_station_id' => 2, 'time_by_car' => 3, 'time_on_foot' => 4],
            ['housing_estate_id' => 2, 'metro_station_id' => 3, 'time_by_car' => 5, 'time_on_foot' => 6],
            ['housing_estate_id' => 2, 'metro_station_id' => 4, 'time_by_car' => 7, 'time_on_foot' => 8],
            ['housing_estate_id' => 3, 'metro_station_id' => 5, 'time_by_car' => 9, 'time_on_foot' => 10],
            ['housing_estate_id' => 3, 'metro_station_id' => 6, 'time_by_car' => 11, 'time_on_foot' => 12],
        ]);
    }
}
