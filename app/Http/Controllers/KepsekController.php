<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KepsekController extends Controller
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
    $data_kepsek = DB::table('kepsek')
        ->join('guru', 'kepsek.id_guru', '=', 'guru.id')
        ->join('users', 'guru.id_user', '=', 'users.id')
        ->select('kepsek.*', 'users.name as nama_kepsek')
        ->get();
        return view('kepsek.data_kepsek', compact('data_kepsek'));
    }
    public function form_tambah_kepsek(){
        return view('kepsek.form_tambah_kepsek');
    }
    public function post_kepsek(Request $request){
        DB::table('kepsek')->insert([
            'id_guru' => $request->id_guru,
        ]);
        return redirect('/data_kepsek')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function hapus_kepsek($id){
        DB::table('kepsek')->where('id', $id)->delete();
        return redirect('/data_kepsek')->with('success', 'Data Berhasil Hapus');
    }
}
