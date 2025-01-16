<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
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
        $data_karyawan = DB::table('karyawan')
            ->join('users', 'karyawan.id_user', '=', 'users.id')
            ->select('karyawan.*', 'users.name as nama_karyawan')
            ->get();
        return view('karyawan.data_karyawan', compact('data_karyawan'));
    }
    public function form_tambah_karyawan(){
        return view('karyawan.form_tambah_karyawan');
    }

    public function post_karyawan(Request $request){
        $request->validate([
            'id_user' => 'required',
            'tugas' => 'required',
        ]);
        $data = [
            'id_user' => $request->id_user,
            'tugas' => $request->tugas,
            'created_at' => now(),
        ];
        DB::table('karyawan')->insert($data);
        return redirect('/data_karyawan')->with('success', 'Data Karyawan Berhasil Tambah');
    }

    public function form_edit_karyawan($id)
    {
        $data_karyawan = DB::table('karyawan')
            ->join('users', 'karyawan.id_user', '=', 'users.id')
            ->where('karyawan.id', $id) // Dipindahkan agar lebih jelas bahwa filter diterapkan ke tabel utama
            ->select('karyawan.*', 'users.name as nama_karyawan')
            ->first();
        return view('karyawan.form_edit_karyawan', compact('data_karyawan'));
    }

    public function update_karyawan(Request $request, $id){
        $request->validate([
            'tugas' => 'required',
        ]);
        $data = [
            'tugas' => $request->tugas,
            'updated_at' => now(),
        ];
        DB::table('karyawan')->where('id', $id)->update($data);
        return redirect('/data_karyawan')->with('success', 'Data Karyawan Berhasil Update');
    }

    public function hapus_karyawan($id){
        DB::table('karyawan')->where('id', $id)->delete();
        return redirect('/data_karyawan')->with('success', 'Data Karyawan Berhasil Hapus');
    }
}
