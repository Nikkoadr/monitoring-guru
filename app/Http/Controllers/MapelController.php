<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapelController extends Controller
{
    public function index()
    {
        $data_mapel = DB::table('mapel')
            ->join('guru_mapel', 'mapel.id_guru', '=', 'guru_mapel.id')
            ->join('users', 'guru_mapel.id_guru', '=', 'users.id')
            ->select('mapel.*', 'guru_mapel.id as id_guru', 'users.name as nama_guru')
            ->get();
            
        return view('data_mapel', compact('data_mapel'));
    }
}
