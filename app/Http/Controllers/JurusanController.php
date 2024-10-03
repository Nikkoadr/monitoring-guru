<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JurusanController extends Controller
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
        $data_jurusan = DB::table('jurusan')->get();
        return view('jurusan.data_jurusan', compact('data_jurusan'));
    }

    public function form_tambah_jurusan()
    {
        return view('jurusan.form_tambah_jurusan');
    }

    public function post_jurusan(Request $request)
    {
        DB::table('jurusan')->insert([
            'nama_jurusan' => $request->nama_jurusan,
        ]);
        return redirect('/jurusan')->with('success', 'Data Jurusan Berhasil Tambah');
    }   

    public function form_edit_jurusan($id)
    {
        $jurusan = DB::table('jurusan')->where('id', $id)->first();
        return view('jurusan.form_edit_jurusan', compact('jurusan'));
    }

    public function update_jurusan(Request $request, $id)
    {
        $request->validate([
            'nama_jurusan' => 'required',
        ]);
        $data = [
            'nama_jurusan' => $request->nama_jurusan,
            'updated_at' => now(),
        ];
        DB::table('jurusan')->where('id', $id)->update($data);
        return redirect('/jurusan')->with('success', 'Data Jurusan Berhasil Edit');
    }

    public function hapus_jurusan($id)
    {
        DB::table('jurusan')->where('id', $id)->delete();
        return redirect('/jurusan')->with('success', 'Data Jurusan Berhasil Hapus');
    }   
}
