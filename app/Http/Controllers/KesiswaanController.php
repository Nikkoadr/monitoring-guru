<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KesiswaanController extends Controller
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
        $data_kesiswaan = DB::table('kesiswaan')
            ->join('guru', 'kesiswaan.id_guru', '=', 'guru.id')
            ->join('users', 'guru.id_user', '=', 'users.id')
            ->select('kesiswaan.*', 'users.name as nama_kesiswaan')
            ->get();

        return view('kesiswaan.data_kesiswaan', compact('data_kesiswaan'));
    }

    public function form_tambah_kesiswaan()
    {
        return view('kesiswaan.form_tambah_kesiswaan');
    }

    public function post_kesiswaan(Request $request)
    {
        $request->validate([
            'id_guru' => 'required',
            'tugas' => 'required',
        ]);
        $data = [
            'id_guru' => $request->id_guru,
            'tugas' => $request->tugas,
            'created_at' => now(),
        ];
        DB::table('kesiswaan')->insert($data);
        return redirect('/data_kesiswaan')->with('success', 'Data kesiswaan berhasil ditambahkan');
    }
    public function form_edit_kesiswaan($id)
    {
        $data_kesiswaan = DB::table('kesiswaan')
            ->join('guru', 'kesiswaan.id_guru', '=', 'guru.id')
            ->join('users', 'guru.id_user', '=', 'users.id')
            ->where('kesiswaan.id', $id)
            ->select('kesiswaan.*', 'users.name as nama_kesiswaan')
            ->first();

        return view('kesiswaan.form_edit_kesiswaan', compact('data_kesiswaan'));
    }

    public function update_kesiswaan(Request $request, $id){
        $request->validate([
            'tugas' => 'required',
        ]);
        $data = [
            'tugas' => $request->tugas,
            'updated_at' => now(),
        ];
        DB::table('kesiswaan')->where('id', $id)->update($data);
        return redirect('/data_kesiswaan')->with('success', 'Data Kesiswaan Berhasil Update');
    }

    public function hapus_kesiswaan(Request $request){
        DB::table('kesiswaan')->where('id', $request->id)->delete();
        return redirect('/data_kesiswaan')->with('success', 'Data Berhasil Dihapus');
    }
}
