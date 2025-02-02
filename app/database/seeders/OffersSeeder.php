<?php

namespace Database\Seeders;

use App\Models\Offer;
use Illuminate\Database\Seeder;

class OffersSeeder extends Seeder
{
    public function run(): void
    {
        Offer::insert([
            [
                'developer_id' => 1,
                'marketplace_id' => 1,
                'housing_estate_id' => 1,
                'name' => 'Оффер 1',
                'external_id' => 0,
                'sip_uri' => 'sipuri-1@example.com',
                'call_limit' => 1,
                'uniqueness_period' => 30,
                'price' => 1000,
                'operator_award' => 0.1,
                'client_is_out_of_town' => 0,
                'looking_not_for_himself' => 0,
                'scenario_id' => 1,
                'active' => 1,
            ],
            [
                'developer_id' => 1,
                'marketplace_id' => 1,
                'housing_estate_id' => 1,
                'name' => 'Оффер 11',
                'external_id' => 0,
                'sip_uri' => 'sipuri-11@example.com',
                'call_limit' => 11,
                'uniqueness_period' => 30,
                'price' => 1000,
                'operator_award' => 0.11,
                'client_is_out_of_town' => 1,
                'looking_not_for_himself' => 1,
                'scenario_id' => 1,
                'active' => 0,
            ],
            [
                'developer_id' => 2,
                'marketplace_id' => 2,
                'housing_estate_id' => 2,
                'name' => 'Оффер 2',
                'external_id' => 0,
                'sip_uri' => 'sipuri-2@example.com',
                'call_limit' => 2,
                'uniqueness_period' => 60,
                'price' => 2000,
                'operator_award' => 0.2,
                'client_is_out_of_town' => 0,
                'looking_not_for_himself' => 0,
                'scenario_id' => 2,
                'active' => 1,
            ],
            [
                'developer_id' => 2,
                'marketplace_id' => 2,
                'housing_estate_id' => 2,
                'name' => 'Оффер 22',
                'external_id' => 0,
                'sip_uri' => 'sipuri-22@example.com',
                'call_limit' => 22,
                'uniqueness_period' => 60,
                'price' => 2000,
                'operator_award' => 0.22,
                'client_is_out_of_town' => 1,
                'looking_not_for_himself' => 1,
                'scenario_id' => 2,
                'active' => 0,
            ],
            [
                'developer_id' => 3,
                'marketplace_id' => 3,
                'housing_estate_id' => 3,
                'name' => 'Оффер 3',
                'external_id' => 0,
                'sip_uri' => 'sipuri-3@example.com',
                'call_limit' => 3,
                'uniqueness_period' => 90,
                'price' => 3000,
                'operator_award' => 0.3,
                'client_is_out_of_town' => 0,
                'looking_not_for_himself' => 0,
                'scenario_id' => 3,
                'active' => 1,
            ],
            [
                'developer_id' => 3,
                'marketplace_id' => 3,
                'housing_estate_id' => 3,
                'name' => 'Оффер 33',
                'external_id' => 0,
                'sip_uri' => 'sipuri-33@example.com',
                'call_limit' => 33,
                'uniqueness_period' => 90,
                'price' => 3000,
                'operator_award' => 0.33,
                'client_is_out_of_town' => 1,
                'looking_not_for_himself' => 1,
                'scenario_id' => 3,
                'active' => 0,
            ],
            [
                'developer_id' => 1,
                'marketplace_id' => 1,
                'housing_estate_id' => null,
                'name' => 'Developer related offer',
                'external_id' => 0,
                'sip_uri' => 'sipuri@example.com',
                'call_limit' => 1,
                'uniqueness_period' => 10,
                'price' => 1000,
                'operator_award' => 0.1,
                'client_is_out_of_town' => 1,
                'looking_not_for_himself' => 1,
                'scenario_id' => 1,
                'active' => 1,
            ],
        ]);
    }
}
