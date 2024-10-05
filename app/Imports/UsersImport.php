<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\User;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'id_role'  => $row['id_role'],
            'gelar_depan'  => $row['gelar_depan'],
            'name'  => $row['name'],
            'gelar_belakang'  => $row['gelar_belakang'],
            'email' => $row['email'],
            'password' => bcrypt($row['password']),
        ]);
    }
}
