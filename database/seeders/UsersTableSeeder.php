<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@smkmuhkandanghaur.sch.id',
            'password' => bcrypt('P4ssw0rd'),
            'id_role' => 1,
        ]);

        User::create([
            'name' => 'Siswa',
            'email' => 'siswa@smkmuhkandanghaur.sch.id',
            'password' => bcrypt('P4ssw0rd'),
            'id_role' => 4,
        ]);

        User::create([
            'name' => 'KM Siswa',
            'email' => 'km_siswa@smkmuhkandanghaur.sch.id',
            'password' => bcrypt('P4ssw0rd'),
            'id_role' => 4,
        ]);

        User::create([
            'name' => 'Kepala Sekolah',
            'email' => 'kepsek@smkmuhkandanghaur.sch.id',
            'password' => bcrypt('P4ssw0rd'),
            'id_role' => 2,
        ]);
        User::create([
            'name' => 'Guru',
            'email' => 'guru@smkmuhkandanghaur.sch.id',
            'password' => bcrypt('P4ssw0rd'),
            'id_role' => 2,
        ]);

        User::create([
            'name' => 'Guru Wali Kelas',
            'email' => 'guru_walas@smkmuhkandanghaur.sch.id',
            'password' => bcrypt('P4ssw0rd'),
            'id_role' => 2,
        ]);

        User::create([
            'name' => 'Guru Waka',
            'email' => 'guru_waka@smkmuhkandanghaur.sch.id',
            'password' => bcrypt('P4ssw0rd'),
            'id_role' => 2,
        ]);

        User::create([
            'name' => 'Guru Kesiswaan',
            'email' => 'guru_kesiswaan@smkmuhkandanghaur.sch.id',
            'password' => bcrypt('P4ssw0rd'),
            'id_role' => 2,
        ]);

        User::create([
            'name' => 'Karyawan',
            'email' => 'karyawan@smkmuhkandanghaur.sch.id',
            'password' => bcrypt('P4ssw0rd'),
            'id_role' => 3,
        ]);
    }
}
