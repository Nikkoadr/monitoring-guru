<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Izin_siswaController extends Controller
{
    public function data_izin_siswa()
    {
        $data_izin_siswa = DB::table('izin_siswa')
            ->join('siswa', 'izin_siswa.id_siswa', '=', 'siswa.id')
            ->join('users', 'siswa.id_user', '=', 'users.id')
            ->join('status_izin', 'izin_siswa.id_status_izin', '=', 'status_izin.id')
            ->select('izin_siswa.*', 'users.name as nama_siswa', 'status_izin.status_izin')
            ->get();
        return view('izin_siswa.data_izin_siswa', compact('data_izin_siswa'));
    }
    public function req_izin_siswa()
    {
        $id_siswa = DB::table('siswa')->where('id_user', Auth::user()->id)->value('id');
        $data_izin_siswa = DB::table('izin_siswa')
            ->join('siswa', 'izin_siswa.id_siswa', '=', 'siswa.id')
            ->join('users', 'siswa.id_user', '=', 'users.id')
            ->join('status_izin', 'izin_siswa.id_status_izin', '=', 'status_izin.id')
            ->select('izin_siswa.*', 'users.name as nama_siswa', 'status_izin.status_izin')
            ->where('izin_siswa.id_siswa', $id_siswa)
            ->get();

        return view('izin_siswa.req_izin_siswa', compact('data_izin_siswa'));
    }

    public function post_izin_siswa(Request $request)
    {
        $id_siswa = DB::table('siswa')
            ->where('id_user', Auth::user()->id)
            ->value('id');
        $id_kelas = DB::table('siswa')
            ->where('id_user', Auth::user()->id)
            ->value('id_kelas');

        $request->validate([
            'alasan' => 'required',
        ]);
        DB::table('izin_siswa')->insert([
            'tanggal' => now()->format('Y-m-d'),
            'jam_keluar' => now()->format('H:i'),
            'id_siswa' => $id_siswa,
            'id_kelas' => $id_kelas,
            'alasan' => $request->alasan,
            'id_status_izin' => '1',
        ]);
        return redirect('/req_izin_siswa')->with('success', 'Data Izin Siswa Bereah Tambah');
    }

    public function edit_izin_siswa($id){
        $data_izin_siswa = DB::table('izin_siswa')
            ->join('siswa', 'izin_siswa.id_siswa', '=', 'siswa.id')
            ->join('users', 'siswa.id_user', '=', 'users.id')
            ->join('status_izin', 'izin_siswa.id_status_izin', '=', 'status_izin.id')
            ->select('izin_siswa.*', 'users.name as nama_siswa', 'status_izin.status_izin')
            ->where('izin_siswa.id', $id)
            ->first();
        $status_izin = DB::table('status_izin')->get();
        return view('izin_siswa.edit_izin_siswa', compact('data_izin_siswa', 'status_izin'));
    }

    public function update_izin_siswa(Request $request, $id){
        $request->validate([
            'tanggal' => 'required',
            'jam_keluar' => 'required',
            'jam_kembali' => 'required',
            'alasan' => 'required',
            'id_status_izin' => 'required',
        ]);
        DB::table('izin_siswa')
            ->where('id', $id)
            ->update([
                'tanggal' => $request->tanggal,
                'jam_keluar' => $request->jam_keluar,
                'jam_kembali' => $request->jam_kembali,
                'alasan' => $request->alasan,
                'id_status_izin' => $request->id_status_izin,
            ]);
        return redirect('/data_izin_siswa')->with('success', 'Data Izin Siswa Bereah Update');
    }

    public function print_surat_izin_siswa($id){
        $data_izin_siswa = DB::table('izin_siswa')
            ->join('siswa', 'izin_siswa.id_siswa', '=', 'siswa.id')
            ->join('users', 'siswa.id_user', '=', 'users.id')
            ->join('kelas', 'izin_siswa.id_kelas', '=', 'kelas.id')
            ->join('status_izin', 'izin_siswa.id_status_izin', '=', 'status_izin.id')
            ->select('izin_siswa.*', 'users.name as nama_siswa', 'kelas.nama_kelas','status_izin.status_izin')
            ->where('izin_siswa.id', $id)
            ->first();
        return view('izin_siswa.print_surat_izin_siswa', compact('data_izin_siswa'));
    }
}
    
    