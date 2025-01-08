<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Status_hadir;

class StatusHadirTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['Hadir', 'Alfa', 'Izin', 'Sakit', 'Bolos'];

        foreach ($statuses as $status) {
            Status_hadir::create(['nama_status_hadir' => $status]);
        }
    }
}
