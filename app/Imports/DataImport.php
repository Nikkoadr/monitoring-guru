<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DataImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'User' => new UsersImport(),
            'Role' => new RoleImport(),
            'Jurusan' => new JurusanImport(),
            'Kelas' => new KelasImport(),
            'Guru' => new GuruImport(),
            'Guru_mapel' => new Guru_mapelImport(),
            'Mapel' => new MapelImport(),
            'Siswa' => new SiswaImport(),
            'Walas' => new WalasImport(),
            'Ketua_kelas' => new Ketua_kelasImport(),
            'Status_hadir' => new Status_hadirImport(),

        ];
    }
}
