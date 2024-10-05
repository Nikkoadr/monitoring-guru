<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Guru_mapel;
use App\Models\Kbm;
use App\Models\Mapel;
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
            'id_jurusan' => '1',
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
            'name' => 'Ini Walas',
            'email' => 'walas@gmail.com',
            'password' => bcrypt('P4ssw0rd'),
            'id_role' => '3',
        ]);
        User::create([
            'name' => 'Ini KM',
            'email' => 'km@gmail.com',
            'password' => bcrypt('P4ssw0rd'),
            'id_role' => '4',
        ]);
        User::create([
            'name' => 'Ini Siswa',
            'email' => 'siswa@gmail.com',
            'password' => bcrypt('P4ssw0rd'),
            'id_role' => '5',
        ]);

        Siswa::create([
            'id_user' => '3',
            'id_kelas' => '1',
            'id_jurusan' => '1',
        ]);

        Guru::create([
            'id_user' => '2',
        ]);
        
        Mapel::create([
            'nama_mapel' => 'Eletronika Dasar',
        ]);

        Guru_mapel::create([
            'id_guru' => '1',
            'id_mapel' => '1',
        ]);

        Kbm::create([
            'id_guru' => '1',
            'id_mapel' => '1',
            'id_kelas' => '1',
            'tanggal' => date('Y-m-d'),
            'jam_ke' => '1',
            'foto_masuk' => NULL,
            'jam_masuk' => NULL,
            'foto_keluar' => NULL,
            'jam_keluar' => NULL,
            'keterangan' => 'cihuy ulala',
        ]);
    }
}
