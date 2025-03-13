<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DataImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'User' => new UsersImport(),
            'Kelas' => new KelasImport(),
            'Guru' => new GuruImport(),
            'Karyawan' => new KaryawanImport(),
            'Siswa' => new SiswaImport(),
            'Walas' => new WalasImport(),
            'Waka' => new WakaImport(),
            'Kesiswaan' => new KesiswaanImport(),
            'Mapel' => new MapelImport(),];

    }
}