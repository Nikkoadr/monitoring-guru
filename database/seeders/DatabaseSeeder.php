<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SettingsTableSeeder::class,
            RolesTableSeeder::class,
            JurusanTableSeeder::class,
            MapelTableSeeder::class,
            KelasTableSeeder::class,
            StatusHadirTableSeeder::class,
            StatusIzinTableSeeder::class,
            UsersTableSeeder::class,
        ]);
    }
}