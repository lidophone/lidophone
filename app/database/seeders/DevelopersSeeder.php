<?php

namespace Database\Seeders;

use App\Models\Developer;
use Illuminate\Database\Seeder;

class DevelopersSeeder extends Seeder
{
    public function run(): void
    {
        Developer::insert([
            ['name' => 'Застройщик 1', 'automatic_handling' => 0],
            ['name' => 'Застройщик 2', 'automatic_handling' => 0],
            ['name' => 'Застройщик 3', 'automatic_handling' => 0],
        ]);
    }
}
