<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Admin',
                'email' => 'admin@lidofon.ru',
                'password' => '$2y$10$n5s/e4Zvl5.E7mxLFSXvXO9bWXDRcgeF5833ls2OsV0H6gFHTNJl6', // lidofonadminpassword
                'transfers_page_only' => 0,
            ],
            [
                'name' => 'Manager',
                'email' => 'manager@lidofon.ru',
                'password' => '$2y$10$n5s/e4Zvl5.E7mxLFSXvXO9bWXDRcgeF5833ls2OsV0H6gFHTNJl6', // lidofonadminpassword
                'transfers_page_only' => 1,
            ],
        ]);
    }
}
