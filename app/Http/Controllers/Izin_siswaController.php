<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Izin_siswaController extends Controller
{
    public function req_izin_siswa()
    {
        $id_siswa = DB::table('siswa')->where('id_user', Auth::user()->id)->value('id');
        $data_izin_siswa = DB::table('izin_siswa')
            ->join('siswa', 'izin_siswa.id_siswa', '=', 'siswa.id')
            ->join('users', 'siswa.id_user', '=', 'users.id')
            ->select('izin_siswa.*', 'users.name as nama_siswa')
            ->where('izin_siswa.id_siswa', $id_siswa)
            ->get();

        return view('izin_siswa.req_izin_siswa', compact('data_izin_siswa'));
    }

    public function post_izin_siswa(Request $request)
    {
        $id_siswa = DB::table('siswa')
            ->where('id_user', Auth::user()->id)
            ->value('id');
        $request->validate([
            'alasan' => 'required',
        ]);
        DB::table('izin_siswa')->insert([
            'tanggal' => now()->format('Y-m-d'),
            'jam' => now()->format('H:i'),
            'id_siswa' => $id_siswa,
            'alasan' => $request->alasan,
            'status' => 'Belum Disetujui',
        ]);
        return redirect('/req_izin_siswa')->with('success', 'Data Izin Siswa Bereah Tambah');
    }
}
    
    