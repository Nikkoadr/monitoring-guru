<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Status_hadir;

class SiswaImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Status_hadir([
            'id_user'  => $row['id_user'],
            'id_kelas'  => $row['id_kelas'],
            'id_jurusan'  => $row['id_jurusan'],
        ]);
    }
}
