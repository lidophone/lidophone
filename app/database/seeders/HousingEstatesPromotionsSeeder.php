<?php

namespace Database\Seeders;

use App\Models\HousingEstatePromotion;
use Illuminate\Database\Seeder;

class HousingEstatesPromotionsSeeder extends Seeder
{
    public function run(): void
    {
        HousingEstatePromotion::insert([
            ['housing_estate_id' => 1, 'promotion_id' => 1],
            ['housing_estate_id' => 1, 'promotion_id' => 2],
            ['housing_estate_id' => 2, 'promotion_id' => 3],
            ['housing_estate_id' => 2, 'promotion_id' => 4],
            ['housing_estate_id' => 3, 'promotion_id' => 5],
            ['housing_estate_id' => 3, 'promotion_id' => 6],
        ]);
    }
}
