<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
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
        $data_kelas = DB::table('kelas')->get();
        return view('kelas.data_kelas', compact('data_kelas'));
    }

    public function form_tambah_kelas()
    {
        return view('kelas.form_tambah_kelas');
    }

    public function post_kelas(Request $request)
    {
        DB::table('kelas')->insert([
            'nama_kelas' => $request->nama_kelas,
        ]);
        return redirect('/data_kelas')->with('success', 'Data Kelas Bereah Tambah');
    }

    public function form_edit_kelas($id)
    {
        $kelas = DB::table('kelas')->where('id', $id)->first();
        return view('kelas.form_edit_kelas', compact('kelas'));
    }

    public function update_kelas(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required',
        ]);
        $data = [
            'nama_kelas' => $request->nama_kelas,
            'updated_at' => now(),
        ];
        DB::table('kelas')->where('id', $id)->update($data);
        return redirect('/data_kelas')->with('success', 'Data Kelas Bereah Edit');
    }   

    public function hapus_kelas($id)
    {
        DB::table('kelas')->where('id', $id)->delete();
        return redirect('/data_kelas')->with('success', 'Data Kelas Bereah Hapus');
    }
}
