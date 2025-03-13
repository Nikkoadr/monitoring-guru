<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Kesiswaan;

class KesiswaanImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Kesiswaan([
            'id_guru'  => $row['id_guru'],
            'tugas'  => $row['tugas'],
        ]);

    }
}
