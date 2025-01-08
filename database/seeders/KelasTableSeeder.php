<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = [
            ['id_jurusan' => 6, 'nama_kelas' => '10-FKK-1'],
            ['id_jurusan' => 6, 'nama_kelas' => '10-FKK-2'],
            ['id_jurusan' => 1, 'nama_kelas' => '10-LAS-1'],
            ['id_jurusan' => 2, 'nama_kelas' => '10-TEI-1'],
            ['id_jurusan' => 4, 'nama_kelas' => '10-TKJ-1'],
            ['id_jurusan' => 4, 'nama_kelas' => '10-TKJ-2'],
            ['id_jurusan' => 4, 'nama_kelas' => '10-TKJ-3'],
            ['id_jurusan' => 4, 'nama_kelas' => '10-TKJ-4'],
            ['id_jurusan' => 3, 'nama_kelas' => '10-TKR-1'],
            ['id_jurusan' => 3, 'nama_kelas' => '10-TKR-2'],
            ['id_jurusan' => 3, 'nama_kelas' => '10-TKR-3'],
            ['id_jurusan' => 3, 'nama_kelas' => '10-TKR-4'],
            ['id_jurusan' => 3, 'nama_kelas' => '10-TKR-5'],
            ['id_jurusan' => 5, 'nama_kelas' => '10-TSM-1'],
            ['id_jurusan' => 5, 'nama_kelas' => '10-TSM-2'],
            ['id_jurusan' => 5, 'nama_kelas' => '10-TSM-3'],
            ['id_jurusan' => 6, 'nama_kelas' => '11-FKK-1'],
            ['id_jurusan' => 1, 'nama_kelas' => '11-LAS-1'],
            ['id_jurusan' => 2, 'nama_kelas' => '11-TEI-1'],
            ['id_jurusan' => 4, 'nama_kelas' => '11-TKJ-1'],
            ['id_jurusan' => 4, 'nama_kelas' => '11-TKJ-2'],
            ['id_jurusan' => 4, 'nama_kelas' => '11-TKJ-3'],
            ['id_jurusan' => 4, 'nama_kelas' => '11-TKJ-4'],
            ['id_jurusan' => 3, 'nama_kelas' => '11-TKR-1'],
            ['id_jurusan' => 3, 'nama_kelas' => '11-TKR-2'],
            ['id_jurusan' => 3, 'nama_kelas' => '11-TKR-3'],
            ['id_jurusan' => 3, 'nama_kelas' => '11-TKR-4'],
            ['id_jurusan' => 3, 'nama_kelas' => '11-TKR-5'],
            ['id_jurusan' => 3, 'nama_kelas' => '11-TKR-6'],
            ['id_jurusan' => 5, 'nama_kelas' => '11-TSM-1'],
            ['id_jurusan' => 5, 'nama_kelas' => '11-TSM-2'],
            ['id_jurusan' => 5, 'nama_kelas' => '11-TSM-3'],
            ['id_jurusan' => 6, 'nama_kelas' => '12-FKK-1'],
            ['id_jurusan' => 6, 'nama_kelas' => '12-FKK-2'],
            ['id_jurusan' => 1, 'nama_kelas' => '12-LAS-1'],
            ['id_jurusan' => 2, 'nama_kelas' => '12-TEI-1'],
            ['id_jurusan' => 4, 'nama_kelas' => '12-TKJ-1'],
            ['id_jurusan' => 4, 'nama_kelas' => '12-TKJ-2'],
            ['id_jurusan' => 4, 'nama_kelas' => '12-TKJ-3'],
            ['id_jurusan' => 4, 'nama_kelas' => '12-TKJ-4'],
            ['id_jurusan' => 3, 'nama_kelas' => '12-TKR-1'],
            ['id_jurusan' => 3, 'nama_kelas' => '12-TKR-2'],
            ['id_jurusan' => 3, 'nama_kelas' => '12-TKR-3'],
            ['id_jurusan' => 3, 'nama_kelas' => '12-TKR-4'],
            ['id_jurusan' => 3, 'nama_kelas' => '12-TKR-5'],
            ['id_jurusan' => 3, 'nama_kelas' => '12-TKR-6'],
            ['id_jurusan' => 5, 'nama_kelas' => '12-TSM-1'],
            ['id_jurusan' => 5, 'nama_kelas' => '12-TSM-2'],
            ['id_jurusan' => 5, 'nama_kelas' => '12-TSM-3'],
        ];

        foreach ($kelas as $data) {
            Kelas::create($data);
        }
    }
}
