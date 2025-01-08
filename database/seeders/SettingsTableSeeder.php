<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Setting::create([
            'nama_aplikasi' => 'SMK Muhammadiyah Kandanghaur',
            'logo_aplikasi' => 'dafault.png',
            'lokasi_latitude' => '-6.363041',
            'lokasi_longitude' => '108.113627',
            'radius_lokasi' => '70',
            'mulai_presensi' => '6:45',
            'limit_presensi' => '13:00',
        ]);
    }
}
