<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WakaController extends Controller
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
    $data_waka = DB::table('waka')
        ->join('guru', 'waka.id_guru', '=', 'guru.id')
        ->join('users', 'guru.id_user', '=', 'users.id')
        ->select('waka.*', 'users.name as nama_waka')
        ->get();
        return view('waka.data_waka', compact('data_waka'));
    }
    
}
