<?php

namespace Database\Seeders;

use App\Models\MetroStation;
use Illuminate\Database\Seeder;

class MetroStationsSeeder extends Seeder
{
    public function run(): void
    {
        MetroStation::insert([
            // MOSCOW
            ['name' => 'Станция 1', 'metro_line_id' => 1, 'under_construction' => 0, 'latitude' => null, 'longitude' => null],
            ['name' => 'Станция 2', 'metro_line_id' => 2, 'under_construction' => 0, 'latitude' => null, 'longitude' => null],
            ['name' => 'Станция 3', 'metro_line_id' => 3, 'under_construction' => 0, 'latitude' => null, 'longitude' => null],
            ['name' => 'Станция 4', 'metro_line_id' => 4, 'under_construction' => 0, 'latitude' => null, 'longitude' => null],
            ['name' => 'Станция 5', 'metro_line_id' => 5, 'under_construction' => 0, 'latitude' => null, 'longitude' => null],
            ['name' => 'Станция 6', 'metro_line_id' => 6, 'under_construction' => 0, 'latitude' => null, 'longitude' => null],
            // MOSCOW - under construction
            ['name' => 'Станция 7', 'metro_line_id' => 7, 'under_construction' => 1, 'latitude' => 55.872960, 'longitude' => 37.602530],
            // SAIT PETERSBURG
            ['name' => 'Станция 8', 'metro_line_id' => 21, 'under_construction' => 0, 'latitude' => null, 'longitude' => null],
            ['name' => 'Станция 9', 'metro_line_id' => 22, 'under_construction' => 0, 'latitude' => null, 'longitude' => null],
            ['name' => 'Станция 10', 'metro_line_id' => 23, 'under_construction' => 0, 'latitude' => null, 'longitude' => null],
            ['name' => 'Станция 11', 'metro_line_id' => 24, 'under_construction' => 0, 'latitude' => null, 'longitude' => null],
            ['name' => 'Станция 12', 'metro_line_id' => 25, 'under_construction' => 0, 'latitude' => null, 'longitude' => null],
            ['name' => 'Станция 13', 'metro_line_id' => 26, 'under_construction' => 0, 'latitude' => null, 'longitude' => null],
        ]);
    }
}
