<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Seeder;

class PromotionsSeeder extends Seeder
{
    public function run(): void
    {
        Promotion::insert([
            ['name' => 'Акция 1', 'link' => 'https://example.com?id=1'],
            ['name' => 'Акция 2', 'link' => 'https://example.com?id=2'],
            ['name' => 'Акция 3', 'link' => 'https://example.com?id=3'],
            ['name' => 'Акция 4', 'link' => 'https://example.com?id=4'],
            ['name' => 'Акция 5', 'link' => 'https://example.com?id=5'],
            ['name' => 'Акция 6', 'link' => 'https://example.com?id=6'],
        ]);
    }
}
