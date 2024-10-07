<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Guru_mapel;

class Guru_mapelImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Guru_mapel([
            'id_mapel'  => $row['id_mapel'],
            'id_guru'  => $row['id_guru'],
        ]);
    }
}
