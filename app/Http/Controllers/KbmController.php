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

    if ($user->id_role == '1') {
        // Jika admin login, tampilkan semua data KBM
        $data_kbm = DB::table('kbm')
            ->join('mapel', 'kbm.id_mapel', '=', 'mapel.id')
            ->join('guru', 'kbm.id_guru', '=', 'guru.id')
            ->join('users', 'guru.id_user', '=', 'users.id')
            ->join('kelas', 'kbm.id_kelas', '=', 'kelas.id')
            ->select('kbm.*', 'mapel.nama_mapel', 'users.name as nama_guru', 'kelas.nama_kelas')
            ->get();
    } else {
        // Jika siswa login, tampilkan data berdasarkan kelas mereka
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
                ->get();
        } else {
            $data_kbm = collect();
        }
    }

    return view('kbm.data_kbm', compact('data_kbm'));
}
}
