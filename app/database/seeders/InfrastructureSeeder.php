<?php

namespace Database\Seeders;

use App\Models\Infrastructure;
use Illuminate\Database\Seeder;

class InfrastructureSeeder extends Seeder
{
    public function run(): void
    {
        Infrastructure::insert([
            ['designation' => 'Обозначение инфраструктуры 1'],
            ['designation' => 'Обозначение инфраструктуры 2'],
            ['designation' => 'Обозначение инфраструктуры 3'],
            ['designation' => 'Обозначение инфраструктуры 4'],
            ['designation' => 'Обозначение инфраструктуры 5'],
            ['designation' => 'Обозначение инфраструктуры 6'],
        ]);
    }
}
