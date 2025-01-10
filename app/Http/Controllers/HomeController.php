<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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
        $hariIni = Carbon::today();

        $kelas = DB::table('kelas')->get();

        $kelasAda = DB::table('kbm')
            ->whereDate('tanggal', $hariIni)
            ->pluck('id_kelas')
            ->toArray();

        $totalKelasHadir = 0;
        $totalJumlahHadir = 0;
        $totalJumlahBelumHadir = 0;

        $kelas = $kelas->map(function ($item) use ($kelasAda, $hariIni, &$totalKelasHadir, &$totalJumlahHadir, &$totalJumlahBelumHadir) {
            $item->status = in_array($item->id, $kelasAda) 
                ? 'Sudah ada di KBM' 
                : 'Kelas ini belum ada di KBM';

            $item->jumlah_hadir = DB::table('absensi_siswa')
                ->where('id_kelas', $item->id)
                ->whereDate('tanggal', $hariIni)
                ->where('id_status_hadir', 1)
                ->count();

            $totalSiswa = DB::table('siswa')
                ->where('id_kelas', $item->id)
                ->count();

            $item->jumlah_belum_hadir = $totalSiswa - $item->jumlah_hadir;

            $item->total_siswa = $totalSiswa;

            if ($item->jumlah_hadir > 0) {
                $totalKelasHadir++;
            }

            $totalJumlahHadir += $item->jumlah_hadir;
            $totalJumlahBelumHadir += $item->jumlah_belum_hadir;

            return $item;
        });


        $userKelas = null;
        if (Gate::allows('siswa')) {
            $userKelas = DB::table('siswa')
                ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id')
                ->where('siswa.id_user', Auth::id())
                ->select('kelas.nama_kelas')
                ->first();
        }

        return view('home', [
            'kelas' => $kelas,
            'hariIni' => $hariIni->toFormattedDateString(),
            'totalKelasHadir' => $totalKelasHadir,
            'totalJumlahHadir' => $totalJumlahHadir,
            'totalJumlahBelumHadir' => $totalJumlahBelumHadir,
            'userKelas' => $userKelas,
        ]);
    }
}
