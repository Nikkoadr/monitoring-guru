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
use App\Models\Status_hadir;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Role::create([
            'nama_role' => 'Admin',
        ]);

        Role::create([
            'nama_role' => 'Wali Kelas',
        ]);

        Role::create([
            'nama_role' => 'Guru Mapel',
        ]);
        Role::create([
            'nama_role' => 'Ketua Kelas',
        ]);
        Role::create([
            'nama_role' => 'Siswa',
        ]);

        Jurusan::create([
            'nama_jurusan' => 'Teknik pengelasan',
        ]);
        
        Jurusan::create([
            'nama_jurusan' => 'Teknik Eletronika Industri',
        ]);

        Jurusan::create([
            'nama_jurusan' => 'Teknik Kendaraan Ringan Otomotif',
        ]);

        Jurusan::create([
            'nama_jurusan' => 'Teknik Komputer dan jaringan',
        ]);

        Jurusan::create([
            'nama_jurusan' => 'Teknik Sepeda Motor',
        ]);

        Jurusan::create([
            'nama_jurusan' => 'Farmasi Klinis dan Komunitas',
        ]);

        Status_hadir::create([
            'status_hadir' => 'Hadir',
        ]);

        Status_hadir::create([
            'status_hadir' => 'Tidak Hadir',
        ]);

        Status_hadir::create([
            'status_hadir' => 'Izin',
        ]);

        Status_hadir::create([
            'status_hadir' => 'Sakit',
        ]);

        Status_hadir::create([
            'status_hadir' => 'Bolos',
        ]);


        // Kelas::create([
        //     'id_jurusan' => '4',
        //     'nama_kelas' => 'X-TKJ-1',
        // ]);
        // Kelas::create([
        //     'id_jurusan' => '4',
        //     'nama_kelas' => 'XI-TKJ-1',
        // ]);
        // Kelas::create([
        //     'id_jurusan' => '4',
        //     'nama_kelas' => 'XII-TKJ-1',
        // ]);
        // Kelas::create([
        //     'id_jurusan' => '3',
        //     'nama_kelas' => 'X-TKR-1',
        // ]);
        // Kelas::create([
        //     'id_jurusan' => '3',
        //     'nama_kelas' => 'XI-TKR-1',
        // ]);
        // Kelas::create([
        //     'id_jurusan' => '3',
        //     'nama_kelas' => 'XII-TKR-1',
        // ]);

        User::create([
            'name' => 'Ini Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('P4ssw0rd'),
            'id_role' => '1',
        ]);
        // User::create([
        //     'name' => 'Ini guru mapel',
        //     'email' => 'guru@gmail.com',
        //     'password' => bcrypt('P4ssw0rd'),
        //     'id_role' => '3',

        // ]);
        // User::create([
        //     'name' => 'Ini Walas',
        //     'email' => 'walas@gmail.com',
        //     'password' => bcrypt('P4ssw0rd'),
        //     'id_role' => '2',
        // ]);
        // User::create([
        //     'name' => 'Ini KM TKJ',
        //     'email' => 'kmtkj@gmail.com',
        //     'password' => bcrypt('P4ssw0rd'),
        //     'id_role' => '4',
        // ]);
        // User::create([
        //     'name' => 'Ini Siswa TKJ',
        //     'email' => 'siswatkj@gmail.com',
        //     'password' => bcrypt('P4ssw0rd'),
        //     'id_role' => '5',
        // ]);
        // User::create([
        //     'name' => 'Ini KM TKR',
        //     'email' => 'kmtkr@gmail.com',
        //     'password' => bcrypt('P4ssw0rd'),
        //     'id_role' => '4',
        // ]);
        // User::create([
        //     'name' => 'Ini Siswa TKR',
        //     'email' => 'siswatkr@gmail.com',
        //     'password' => bcrypt('P4ssw0rd'),
        //     'id_role' => '5',
        // ]);

        // Siswa::create([
        //     'id_user' => '4',
        //     'id_kelas' => '1',
        //     'id_jurusan' => '4',
        // ]);
        // Siswa::create([
        //     'id_user' => '5',
        //     'id_kelas' => '2',
        //     'id_jurusan' => '4',
        // ]);
        // Siswa::create([
        //     'id_user' => '6',
        //     'id_kelas' => '4',
        //     'id_jurusan' => '3',
        // ]);
        // Siswa::create([
        //     'id_user' => '7',
        //     'id_kelas' => '5',
        //     'id_jurusan' => '3',
        // ]);

        // Guru::create([
        //     'id_user' => '2',
        // ]);
        // Guru::create([
        //     'id_user' => '3',
        // ]);
        
        // Mapel::create([
        //     'nama_mapel' => 'Eletronika Dasar',
        // ]);

        // Guru_mapel::create([
        //     'id_guru' => '1',
        //     'id_mapel' => '1',
        // ]);

        // Kbm::create([
        //     'id_guru' => '1',
        //     'id_mapel' => '1',
        //     'id_kelas' => '1',
        //     'tanggal' => date('Y-m-d'),
        //     'jam_ke' => '1',
        //     'foto_masuk' => NULL,
        //     'jam_masuk' => NULL,
        //     'foto_keluar' => NULL,
        //     'jam_keluar' => NULL,
        //     'keterangan' => 'cihuy ulala',
        // ]);
    }
}
