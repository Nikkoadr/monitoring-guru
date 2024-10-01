<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\Guru_mapel;
use App\Models\Mapel;
use App\Models\Monitoring;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();



        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
