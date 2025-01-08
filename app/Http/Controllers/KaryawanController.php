<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    public function index(){
        $data_karyawan = DB::table('karyawan')
            ->join('users', 'karyawan.id_user', '=', 'users.id')
            ->select('karyawan.*', 'users.name as nama_karyawan')
            ->get();
        return view('karyawan.data_karyawan', compact('data_karyawan'));
    }
}