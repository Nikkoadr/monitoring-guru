<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KbmController extends Controller
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
    $user = Auth::user();
    
    // Mendapatkan tanggal hari ini
    $today = now()->toDateString();

    // Cek peran berdasarkan id_role
    if ($user->id_role == 1) {
        // Jika admin login, tampilkan semua data KBM pada hari ini
        $data_kbm = DB::table('kbm')
            ->join('mapel', 'kbm.id_mapel', '=', 'mapel.id')
            ->join('guru', 'kbm.id_guru', '=', 'guru.id')
            ->join('users', 'guru.id_user', '=', 'users.id')
            ->join('kelas', 'kbm.id_kelas', '=', 'kelas.id')
            ->select('kbm.*', 'mapel.nama_mapel', 'users.name as nama_guru', 'kelas.nama_kelas')
            ->whereDate('kbm.tanggal', $today)  // Filter berdasarkan tanggal hari ini
            ->get();
    } elseif ($user->id_role == 2) {
        // Jika guru mata pelajaran login, tampilkan hanya data mata pelajaran yang diampu pada hari ini
        $guru = DB::table('guru')
            ->where('id_user', $user->id)
            ->first();

        if ($guru) {
            $data_kbm = DB::table('kbm')
                ->join('mapel', 'kbm.id_mapel', '=', 'mapel.id')
                ->join('guru', 'kbm.id_guru', '=', 'guru.id')
                ->join('users', 'guru.id_user', '=', 'users.id')
                ->join('kelas', 'kbm.id_kelas', '=', 'kelas.id')
                ->select('kbm.*', 'mapel.nama_mapel', 'users.name as nama_guru', 'kelas.nama_kelas')
                ->where('kbm.id_guru', $guru->id)  // Filter berdasarkan guru mata pelajaran
                ->whereDate('kbm.tanggal', $today)  // Filter berdasarkan tanggal hari ini
                ->get();
        } else {
            $data_kbm = collect();
        }
    } elseif ($user->id_role == 3) {
        // Jika wali kelas login, tampilkan hanya data untuk kelas yang dipegangnya pada hari ini
        $wali_kelas = DB::table('guru')
            ->where('id_user', $user->id)
            ->first();

        if ($wali_kelas) {
            $data_kbm = DB::table('kbm')
                ->join('mapel', 'kbm.id_mapel', '=', 'mapel.id')
                ->join('guru', 'kbm.id_guru', '=', 'guru.id')
                ->join('users', 'guru.id_user', '=', 'users.id')
                ->join('kelas', 'kbm.id_kelas', '=', 'kelas.id')
                ->select('kbm.*', 'mapel.nama_mapel', 'users.name as nama_guru', 'kelas.nama_kelas')
                ->where('kbm.id_kelas', $wali_kelas->id_kelas)  // Filter berdasarkan kelas wali kelas
                ->whereDate('kbm.tanggal', $today)  // Filter berdasarkan tanggal hari ini
                ->get();
        } else {
            $data_kbm = collect();
        }
    } elseif ($user->id_role == 5) {
        // Jika siswa login, tampilkan data berdasarkan kelas mereka pada hari ini
        $siswa = DB::table('siswa')
            ->where('id_user', $user->id)
            ->first();

        if ($siswa) {
            $data_kbm = DB::table('kbm')
                ->join('mapel', 'kbm.id_mapel', '=', 'mapel.id')
                ->join('guru', 'kbm.id_guru', '=', 'guru.id')
                ->join('users', 'guru.id_user', '=', 'users.id')
                ->join('kelas', 'kbm.id_kelas', '=', 'kelas.id')
                ->select('kbm.*', 'mapel.nama_mapel', 'users.name as nama_guru', 'kelas.nama_kelas')
                ->where('kbm.id_kelas', $siswa->id_kelas)  // Filter berdasarkan kelas siswa
                ->whereDate('kbm.tanggal', $today)  // Filter berdasarkan tanggal hari ini
                ->get();
        } else {
            $data_kbm = collect();
        }
    } else {
        $data_kbm = collect();  // Tidak ada peran yang cocok, tampilkan data kosong
    }

    return view('kbm.data_kbm', compact('data_kbm','user'));
}


}
