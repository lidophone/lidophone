<?php

namespace Database\Seeders;

use App\Models\HousingEstateTag;
use Illuminate\Database\Seeder;

class HousingEstatesTagsSeeder extends Seeder
{
    public function run(): void
    {
        HousingEstateTag::insert([
            ['housing_estate_id' => 1, 'tag_id' => 1],
            ['housing_estate_id' => 1, 'tag_id' => 2],
            ['housing_estate_id' => 2, 'tag_id' => 3],
            ['housing_estate_id' => 2, 'tag_id' => 4],
            ['housing_estate_id' => 3, 'tag_id' => 5],
            ['housing_estate_id' => 3, 'tag_id' => 6],
        ]);
    }
}
