<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    public function index(){
        $data_guru = DB::table('guru_mapel')
            ->join('users', 'guru_mapel.id_guru', '=', 'users.id')
            ->select('guru_mapel.*', 'users.name as nama_guru')
            ->get();
        return view('data_guru', compact('data_guru'));
    }

}
