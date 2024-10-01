<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
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
    public function index(){
        $data_guru = DB::table('guru_mapel')
            ->join('users', 'guru_mapel.id_user', '=', 'users.id')
            ->select('guru_mapel.*', 'users.name as nama_guru')
            ->get();
        return view('data_guru', compact('data_guru'));
    }
    public function form_tambah_guru(Request $request){
        return view('form_tambah_guru');
    }

}
