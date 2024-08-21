<?php

namespace Database\Seeders;

use App\Models\MetroLine;
use Illuminate\Database\Seeder;

class MetroLinesSeeder extends Seeder
{
    const LINES = [
        // Moscow
        // https://is.gd/WZaUi5
        1 => [
            ['Сокольническая', '#d41317', 1],
            ['Замоскворецкая', '#4aaf50', 2],
            ['Арбатско-Покровская', '#0072ba', 3],
            ['Филёвская', '#1ebcef', 4],
            ['Кольцевая', '#915133', 5],
            ['Калужско-Рижская', '#ef7d00', 6],
            ['Таганско-Краснопресненская', '#943e90', 7],
            ['Калининская', '#ffdd00', 8],
            ['Солнцевская', '#ffdd00', '8A'], // Latin "A"
            ['Серпуховско-Тимирязевская', '#adabac', 9],
            ['Люблинско-Дмитровская', '#b4cc04', 10],
            ['Большая кольцевая', '#87cccf', 11],
            ['Бутовская', '#bac8e8', 12],
            // Missing "Moscow monorail" (designation: 13): https://is.gd/RpzE3e
            ['МЦК', '#d41317', 14],
            ['Некрасовская', '#ee7bae', 15],
            // https://is.gd/EPBlAh
            ['МЦД-1', '#f6a700', 'D1'], // https://is.gd/O2H1cS
            ['МЦД-2', '#ea4083', 'D2'], // https://is.gd/ua4Eqt
            ['МЦД-3', '#ea5b04', 'D3'], // https://is.gd/UrMJdM
            ['МЦД-4', '#3fb485', 'D4'], // https://is.gd/G5Gq2u
            ['МЦД-5', '#78b824', 'D5'], // https://is.gd/Bh2oAd
        ],

        // SAINT PETERSBURG
        // https://is.gd/q28zgG
        2 => [
            ['Кировско-Выборгская'      , '#d70238', 1], // https://is.gd/l4Vx4t
            ['Московско-Петроградская'  , '#0079ca', 2], // https://is.gd/apoTHZ
            ['Невско-Василеостровская'  , '#009b47', 3], // https://is.gd/98tsHf
            ['Правобережная'            , '#eb7220', 4], // https://is.gd/jSrIRH
            ['Фрунзенско-Приморская'    , '#712286', 5], // https://is.gd/OjoW0h
            ['Красносельско-Калининская', '#8e5b29', 6], // https://is.gd/tL97np
            ['Кольцевая'                , '#999999', 7], // https://is.gd/6sJrKT
            ['Адмиралтейско-Охтинская'  , '#86d5f3', 8], // https://is.gd/N2NB6p
        ],
    ];

    public function run(): void
    {
        $data = [];
        foreach (self::LINES as $cityId => $lines) {
            foreach ($lines as $line) {
                $data[] = [
                    'city_id' => $cityId,
                    'name' => $line[0],
                    'color' => $line[1],
                    'designation' => $line[2],
                ];
            }
        }
        MetroLine::insert($data);
    }
}
