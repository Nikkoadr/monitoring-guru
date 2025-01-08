<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KepsekController extends Controller
{
    public function index(){
    $data_kepsek = DB::table('kepsek')
        ->join('guru', 'kepsek.id_guru', '=', 'guru.id')
        ->join('users', 'guru.id_user', '=', 'users.id')
        ->select('kepsek.*', 'users.name as nama_kepsek')
        ->get();
        return view('kepsek.data_kepsek', compact('data_kepsek'));
    }
}
