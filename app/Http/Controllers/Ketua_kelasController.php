<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Ketua_kelasController extends Controller{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $data_ketua_kelas = DB::table('ketua_kelas')
            ->join('siswa', 'ketua_kelas.id_siswa', '=', 'siswa.id')
            ->join('users', 'siswa.id_user', '=', 'users.id')
            ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id')
            ->select('ketua_kelas.*', 'users.name as nama_ketua_kelas', 'kelas.nama_kelas')
            ->get();


        return view('ketua_kelas.data_ketua_kelas', compact('data_ketua_kelas'));
    }
}
