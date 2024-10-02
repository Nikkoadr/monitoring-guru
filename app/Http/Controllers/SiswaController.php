<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
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
        $data_siswa = DB::table('siswa')
            ->join('users', 'siswa.id_user', '=', 'users.id')
            ->select('siswa.*', 'users.name as nama_siswa')
            ->get();
            return view('siswa.data_siswa', compact('data_siswa'));
    }

}
