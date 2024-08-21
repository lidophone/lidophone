<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsersSeeder::class,
            CitiesSeeder::class,
            MetroLinesSeeder::class,

            DevelopersSeeder::class,
            HousingEstatesSeeder::class,
            InfrastructureSeeder::class,
            HousingEstatesInfrastructureSeeder::class,
            MarketplacesSeeder::class,
            MetroStationsSeeder::class,
            HousingEstatesMetroStationsSeeder::class,
            PaymentMethodsSeeder::class,
            HousingEstatesPaymentMethodsSeeder::class,
            PromotionsSeeder::class,
            HousingEstatesPromotionsSeeder::class,
            TagsSeeder::class,
            HousingEstatesTagsSeeder::class,

            ObjectsSeeder::class,
            ScenariosSeeder::class,
            OffersSeeder::class,
            WorkingHoursSeeder::class,

            SettingsSeeder::class,
        ]);
    }
}
