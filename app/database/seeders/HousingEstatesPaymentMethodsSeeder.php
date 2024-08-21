<?php

namespace Database\Seeders;

use App\Models\HousingEstatePaymentMethod;
use Illuminate\Database\Seeder;

class HousingEstatesPaymentMethodsSeeder extends Seeder
{
    public function run(): void
    {
        HousingEstatePaymentMethod::insert([
            ['housing_estate_id' => 1, 'payment_method_id' => 1],
            ['housing_estate_id' => 1, 'payment_method_id' => 2],
            ['housing_estate_id' => 2, 'payment_method_id' => 3],
            ['housing_estate_id' => 2, 'payment_method_id' => 4],
            ['housing_estate_id' => 3, 'payment_method_id' => 5],
            ['housing_estate_id' => 3, 'payment_method_id' => 6],
        ]);
    }
}
