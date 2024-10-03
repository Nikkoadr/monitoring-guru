<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapelController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data_mapel = DB::table('mapel')
            ->join('guru', 'mapel.id_guru', '=', 'guru.id')
            ->join('users', 'guru.id_user', '=', 'users.id')
            ->select('mapel.*', 'guru.id as id_guru', 'users.name as nama_guru')
            ->get();
            
        return view('mapel.data_mapel', compact('data_mapel'));
    }

    public function form_tambah_mapel()
    {
        $guru = DB::table('guru')->get();
        return view('mapel.form_tambah_mapel', compact('guru'));
    }

    public function get_guru(Request $request)
    {
        $data = DB::table('guru')->where('id_user', $request->id)->get();
        return response()->json($data);
    }
}
