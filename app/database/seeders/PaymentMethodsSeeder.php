<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodsSeeder extends Seeder
{
    public function run(): void
    {
        PaymentMethod::insert([
            ['name' => 'Способ 1'],
            ['name' => 'Способ 2'],
            ['name' => 'Способ 3'],
            ['name' => 'Способ 4'],
            ['name' => 'Способ 5'],
            ['name' => 'Способ 6'],
        ]);
    }
}
