<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DataImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'User' => new UsersImport(),
            // 'Kelas' => new KelasImport(),
            // 'Guru' => new GuruImport(),
            // 'Mapel' => new MapelImport(),
            // 'Guru_mapel' => new Guru_mapelImport(),
            // 'Siswa' => new SiswaImport(),
            // 'Walas' => new WalasImport(),
            // 'Ketua_kelas' => new Ketua_kelasImport(),

        ];
    }
}