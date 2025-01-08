<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mapel;

class MapelTableSeeder extends Seeder
{
    public function run(): void
    {
        $mapel = [
            'PAI dan Budi Pekerti',
            'Bahasa Arab',
            'PPKN',
            'Bahasa Inggris',
            'Bahasa Indonesia',
            'Matematika',
            'Matematika Pilihan',
            'Sejarah Indonesia',
            'PJOK',
            'IPAS',
            'Seni Budaya',
            'Informatika',
            'Kemuhammadiyahan',
            'Al – Qur’an (BTQ)',
            'Produk Kreatif & Kewirausahaan',
            'Konsentrasi Keahlian LAS',
            'Konsentrasi Keahlian TEI',
            'Konsentrasi Keahlian TKR',
            'Konsentrasi Keahlian TKJ',
            'Konsentrasi Keahlian TSM',
            'Konsentrasi Keahlian FKK',
        ];

        foreach ($mapel as $data) {
            Mapel::create(['nama_mapel' => $data]);
        }
    }
}
