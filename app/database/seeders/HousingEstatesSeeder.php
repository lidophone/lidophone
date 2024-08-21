<?php

namespace Database\Seeders;

use App\Models\HousingEstate;
use Illuminate\Database\Seeder;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class HousingEstatesSeeder extends Seeder
{
    public function run(): void
    {
        HousingEstate::insert([
            [
                'name' => 'ЖК 1',
                'developer_id' => 1,
                'city_id' => 1,
                'is_region' => 0,
                'latitude' => '55.755206',
                'longitude' => '37.410229',
                'site' => 'https://example.org?id=1',
                'location' => 'Расположение 1',
                'advantages' => 'Преимущество 1',
                'payment' => '- Оплата 1
- Оплата 2
- Оплата 3',
            ],
            [
                'name' => 'ЖК 2',
                'developer_id' => 2,
                'city_id' => 1,
                'is_region' => 1,
                'latitude' => '55.752828',
                'longitude' => '37.617865',
                'site' => 'https://example.org?id=2',
                'location' => 'Расположение 2',
                'advantages' => 'Преимущество 2',
                'payment' => '- Оплата 4
- Оплата 5
- Оплата 6',
            ],
            [
                'name' => 'ЖК 3',
                'developer_id' => 3,
                'city_id' => 1,
                'is_region' => 0,
                'latitude' => '55.765533',
                'longitude' => '37.790870',
                'site' => 'https://example.org?id=3',
                'location' => 'Расположение 3',
                'advantages' => 'Преимущество 3',
                'payment' => '- Оплата 7
- Оплата 8
- Оплата 9',
            ],
            [
                'name' => 'ЖК 4',
                'developer_id' => 1,
                'city_id' => 1,
                'is_region' => 0,
                'latitude' => '55.652177',
                'longitude' => '37.639158',
                'site' => 'https://example.org?id=4',
                'location' => 'Расположение 4',
                'advantages' => 'Преимущество 4',
                'payment' => '- Оплата 10
- Оплата 11
- Оплата 12',
            ],
            [
                'name' => 'ЖК 5 (For developer related offer)',
                'developer_id' => 1,
                'city_id' => 1,
                'is_region' => 0,
                'latitude' => '55.826645',
                'longitude' => '37.605936',
                'site' => 'https://example.org?id=5',
                'location' => 'Расположение 5',
                'advantages' => 'Преимущество 5',
                'payment' => '- Оплата 13
- Оплата 14
- Оплата 15',
            ],
        ]);

        $media = [];
        for ($i = 0, $j = 0; $i < 9; $i++) {
            mkdir(storage_path('app/public/' . ($i + 1)));
            copy(
                database_path('seeders/img/' . $i + 1 . '.jpg'),
                storage_path('app/public/' . $i + 1) . '/' . $i + 1 . '.jpg'
            );
            if ($i % 3 === 0) {
                $j++;
            }
            $media[] = [
                'id' => $i + 1,
                'model_type' => HousingEstate::class,
                'model_id' => $j,
                'collection_name' => 'images',
                'name' => $i + 1,
                'file_name' => $i + 1 . '.jpg',
                'disk' => 'public',
                'size' => 1000,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'generated_conversions' => '[]',
                'responsive_images' => '[]',
            ];
        }
        Media::insert($media);
    }
}
