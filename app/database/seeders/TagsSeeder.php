<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagsSeeder extends Seeder
{
    public function run(): void
    {
        Tag::insert([
            ['name' => 'Тег 1'],
            ['name' => 'Тег 2'],
            ['name' => 'Тег 3'],
            ['name' => 'Тег 4'],
            ['name' => 'Тег 5'],
            ['name' => 'Тег 6'],
        ]);
    }
}
