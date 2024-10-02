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

        Role::create([
            'nama_role' => 'admin',
        ]);

        Role::create([
            'nama_role' => 'guru',
        ]);

        Role::create([
            'nama_role' => 'siswa',
        ]);

        Jurusan::create([
            'nama_jurusan' => 'Teknik Komputer dan Jaringan',
        ]);

        Kelas::create([
            'nama_kelas' => 'X-TKJ-1',
        ]);

        User::create([
            'name' => 'Ini Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('P4ssw0rd'),
            'id_role' => '1',

        ]);
        User::create([
            'name' => 'Ini guru',
            'email' => 'guru@gmail.com',
            'password' => bcrypt('P4ssw0rd'),
            'id_role' => '2',

        ]);
        User::create([
            'name' => 'Ini Siswa',
            'email' => 'siswa@gmail.com',
            'password' => bcrypt('P4ssw0rd'),
            'id_role' => '3',
        ]);

        Siswa::create([
            'id_user' => '3',
            'id_kelas' => '1',
            'id_jurusan' => '1',
        ]);

        Guru_mapel::create([
            'id_user' => '2',
        ]);
        
        Mapel::create([
            'nama_mapel' => 'Administrasi Jaringan',
            'id_guru' => '1',
        ]);
    }
}
