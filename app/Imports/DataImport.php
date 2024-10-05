<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DataImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Role' => new RoleImport(),
            'User' => new UsersImport(),
            'Jurusan' => new JurusanImport(),
            'Kelas' => new KelasImport(),
            'Guru' => new GuruImport(),
            'Mapel' => new MapelImport(),
            'Guru_mapel' => new Guru_mapelImport(),
            'Siswa' => new SiswaImport(),
            'Walas' => new WalasImport(),
            'Ketua_kelas' => new Ketua_kelasImport(),
            'Status_hadir' => new Status_hadirImport(),

        ];
    }
}