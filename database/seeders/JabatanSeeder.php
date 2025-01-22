<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jabatan;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                $jabatan = [
                    'Kepala Sekolah',
                    'Kurikulum',
                    'Humas',
                    'Kepala BKK',
                    'Kesiswaan',
                    'Kepala TU',
                    'Sarana',
                    'Bendahara',
                    'Kepala Prodi',
                ];

        foreach ($jabatan as $data) {
            Jabatan::create(['nama_jabatan' => $data]);
        }
    }
}
