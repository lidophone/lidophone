<?php

namespace Database\Seeders;

use App\Enums\Deadline;
use App\Enums\Finishing;
use App\Enums\RealEstateType;
use App\Enums\Roominess;
use App\Models\Objects;
use Illuminate\Database\Seeder;

class ObjectsSeeder extends Seeder
{
    public function run(): void
    {
        $objects = [];
        $nextYear = (int) date('Y') + 1;
        for ($i = 1; $i <= 1; $i++) { // Housing estates
            for ($j = 0; $j <= 4; $j++) { // Done, I qr. <nextYear>, II qr. <nextYear>, ..., IV qr. <nextYear>
                foreach (RealEstateType::cases() as $realEstateType) { // Real estate type
                    foreach (Finishing::cases() as $finishingCase) { // Finishing
                        foreach (Roominess::cases() as $roominessCase) { // Roominess
                            $objectData = [
                                'housing_estate_id' => $i,
                                'roominess' => $roominessCase->value,
                                'real_estate_type' => $realEstateType->value,
                                'finishing' => $finishingCase->value,
                                'square_meters' => $i * 100,
                                'price' => $i * 10000000,
                            ];
                            if ($j === 0) { // Done
                                $objectData['done'] = 1;
                                $objectData['deadline_year'] = null;
                                $objectData['deadline_quarter'] = null;
                            } else {
                                $objectData['done'] = 0;
                                $objectData['deadline_year'] = $nextYear;
                                $objectData['deadline_quarter'] = $j;
                            }
                            $objects[] = $objectData;
                        }
                    }
                }
            }
        }
        shuffle($objects);
        Objects::insert($objects);
        Objects::insert([
            [
                'housing_estate_id' => 2,
                'roominess' => Roominess::TYPE_2E->value,
                'real_estate_type' => 0,
                'finishing' => Finishing::Without_finishing->value,
                'square_meters' => 200,
                'price' => 20000000,
                'done' => 0,
                'deadline_year' => $nextYear,
                'deadline_quarter' => Deadline::II->value,
            ],
            [
                'housing_estate_id' => 2,
                'roominess' => Roominess::TYPE_2K->value,
                'real_estate_type' => 1,
                'finishing' => Finishing::Pre_finishing->value,
                'square_meters' => 220,
                'price' => 22000000,
                'done' => 1,
                'deadline_year' => null,
                'deadline_quarter' => null,
            ],
            [
                'housing_estate_id' => 3,
                'roominess' => Roominess::TYPE_3E->value,
                'real_estate_type' => 0,
                'finishing' => Finishing::Pre_finishing->value,
                'square_meters' => 300,
                'price' => 30000000,
                'done' => 0,
                'deadline_year' => $nextYear,
                'deadline_quarter' => Deadline::III->value,
            ],
            [
                'housing_estate_id' => 3,
                'roominess' => Roominess::TYPE_3K->value,
                'real_estate_type' => 1,
                'finishing' => Finishing::With_renovation->value,
                'square_meters' => 330,
                'price' => 33000000,
                'done' => 1,
                'deadline_year' => null,
                'deadline_quarter' => null,
            ],
            [
                'housing_estate_id' => 5,
                'roominess' => Roominess::TYPE_1K->value,
                'real_estate_type' => 1,
                'finishing' => Finishing::With_renovation->value,
                'square_meters' => 100,
                'price' => 10000000,
                'done' => 1,
                'deadline_year' => null,
                'deadline_quarter' => null,
            ],
            [
                'housing_estate_id' => 5,
                'roominess' => Roominess::TYPE_2K->value,
                'real_estate_type' => 1,
                'finishing' => Finishing::With_renovation->value,
                'square_meters' => 200,
                'price' => 20000000,
                'done' => 1,
                'deadline_year' => null,
                'deadline_quarter' => null,
            ],
        ]);
    }
}
