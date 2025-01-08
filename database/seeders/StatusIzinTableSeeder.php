<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Status_izin;

class StatusIzinTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['Belum Disetujui', 'Disetujui', 'Ditolak'];

        foreach ($statuses as $status) {
            Status_izin::create(['nama_status_izin' => $status]);
        }
    }
}
