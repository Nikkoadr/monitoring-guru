<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jurusan;

class JurusanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusan = [
                    'Teknik pengelasan',
                    'Teknik Eletronika Industri',
                    'Teknik Kendaraan Ringan Otomotif',
                    'Teknik Komputer dan jaringan',
                    'Teknik Sepeda Motor',
                    'Farmasi Klinis dan Komunitas'
                ];

        foreach ($jurusan as $data) {
            Jurusan::create(['nama_jurusan' => $data]);
        }
    }
}
