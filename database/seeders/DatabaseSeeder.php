<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Jurusan;
use App\Models\Role;
use App\Models\Status_hadir;
use App\Models\Status_izin;
use App\Models\Setting;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Setting::create([
            'nama_aplikasi' => 'SMK Muhammadiyah Kandanghaur',
            'logo_aplikasi' => 'dafault.png',
            'lokasi_latitude' => '-6.363041',
            'lokasi_longitude' => '108.113627',
            'radius_lokasi' => '70',
            'mulai_presensi' => '6:45',
            'limit_presensi' => '13:00',
        ]);

        Role::create([
            'nama_role' => 'Admin',
        ]);

        Role::create([
            'nama_role' => 'Guru',
        ]);

        Role::create([
            'nama_role' => 'Karyawan',
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
            'status_hadir' => 'Alfa',
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
        

        Status_izin::create([
            'status_izin' => 'Belum Disetujui',
        ]);

        Status_izin::create([
            'status_izin' => 'Disetujui',
            
        ]);

        Status_izin::create([
            'status_izin' => 'Ditolak',
        ]);

        User::create([
            'name' => 'Administrator',
            'email' => 'admin@smkmuhkandanghaur.sch.id',
            'password' => bcrypt('P4ssw0rd'),
            'id_role' => '1',
        ]);
    }
}
