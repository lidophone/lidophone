<?php

namespace Database\Seeders;

use App\Models\Marketplace;
use Illuminate\Database\Seeder;

class MarketplacesSeeder extends Seeder
{
    public function run(): void
    {
        Marketplace::insert([
            ['name' => 'Площадка 1'],
            ['name' => 'Площадка 2'],
            ['name' => 'Площадка 3'],
            ['name' => 'Площадка 4'],
            ['name' => 'Площадка 5'],
            ['name' => 'Площадка 6'],
        ]);
    }
}
