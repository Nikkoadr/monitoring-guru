<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KesiswaanController extends Controller
{
    public function index(){
        $data_kesiswaan = DB::table('kesiswaan')
            ->join('guru', 'kesiswaan.id_guru', '=', 'guru.id')
            ->join('users', 'guru.id_user', '=', 'users.id')
            ->select('kesiswaan.*', 'users.name as nama_guru')
            ->get();

        return view('kesiswaan.data_kesiswaan', compact('data_kesiswaan'));
    }
}
