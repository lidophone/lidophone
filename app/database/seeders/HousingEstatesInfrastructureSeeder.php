<?php

namespace Database\Seeders;

use App\Models\HousingEstateInfrastructure;
use Illuminate\Database\Seeder;

class HousingEstatesInfrastructureSeeder extends Seeder
{
    public function run(): void
    {
        HousingEstateInfrastructure::insert([
            [
                'housing_estate_id' => 1,
                'infrastructure_id' => 1,
                'name' => 'Название инфраструктуры 1',
                'time_on_foot' => 1,
                'time_by_car' => 2,
            ],
            [
                'housing_estate_id' => 1,
                'infrastructure_id' => 2,
                'name' => 'Название инфраструктуры 2',
                'time_on_foot' => 3,
                'time_by_car' => 4,
            ],
            [
                'housing_estate_id' => 2,
                'infrastructure_id' => 3,
                'name' => 'Название инфраструктуры 3',
                'time_on_foot' => 5,
                'time_by_car' => 6,
            ],
            [
                'housing_estate_id' => 2,
                'infrastructure_id' => 4,
                'name' => 'Название инфраструктуры 4',
                'time_on_foot' => 7,
                'time_by_car' => 8,
            ],
            [
                'housing_estate_id' => 3,
                'infrastructure_id' => 5,
                'name' => 'Название инфраструктуры 5',
                'time_on_foot' => 9,
                'time_by_car' => 10,
            ],
            [
                'housing_estate_id' => 3,
                'infrastructure_id' => 6,
                'name' => 'Название инфраструктуры 6',
                'time_on_foot' => 11,
                'time_by_car' => 12,
            ],
        ]);
    }
}
