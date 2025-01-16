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

    public function form_tambah_waka(){
        return view('waka.form_tambah_waka');
    }
    public function post_waka(Request $request){
        $request->validate([
            'id_guru' => 'required',
            'jabatan' => 'required',
        ]);
        $data = [
            'id_guru' => $request->id_guru,
            'jabatan' => $request->jabatan,
            'created_at' => now(),
        ];
        DB::table('waka')->insert($data);
        return redirect('data_waka')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function form_edit_waka($id)
    {
        $data_waka = DB::table('waka')
            ->join('guru', 'waka.id_guru', '=', 'guru.id')
            ->join('users', 'guru.id_user', '=', 'users.id')
            ->where('waka.id', $id) // Pindahkan where untuk membuat query lebih terstruktur
            ->select('waka.*', 'users.name as nama_waka')
            ->first();

        if (!$data_waka) {
            return redirect()->route('waka.index')->with('error', 'Data Wakasek tidak ditemukan.');
        }

        return view('waka.form_edit_waka', compact('data_waka'));
    }

    public function update_waka(Request $request, $id){
        $request->validate([
            'jabatan' => 'required',
        ]);
        $data = [
            'jabatan' => $request->jabatan,
            'updated_at' => now(),
        ];
        DB::table('waka')->where('id', $id)->update($data);
        return redirect('data_waka')->with('success', 'Data Berhasil Update');
    }

    public function hapus_waka(Request $request){
        DB::table('waka')->where('id', $request->id)->delete();
        return redirect('data_waka')->with('success', 'Data Berhasil Dihapus');
    }
    
}
