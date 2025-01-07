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
        // Tanggal hari ini
        $hariIni = Carbon::today();

        // Ambil semua kelas
        $kelas = DB::table('kelas')->get();

        // Ambil ID kelas yang sudah ada di tabel KBM pada hari ini
        $kelasAda = DB::table('kbm')
            ->whereDate('tanggal', $hariIni)
            ->pluck('id_kelas')
            ->toArray();

        // Inisialisasi total
        $totalKelasHadir = 0; // Total kelas dengan kehadiran
        $totalJumlahHadir = 0; // Total seluruh siswa hadir
        $totalJumlahBelumHadir = 0; // Total seluruh siswa belum hadir

        // Tambahkan status dan jumlah siswa ke setiap kelas
        $kelas = $kelas->map(function ($item) use ($kelasAda, $hariIni, &$totalKelasHadir, &$totalJumlahHadir, &$totalJumlahBelumHadir) {
            // Status KBM
            $item->status = in_array($item->id, $kelasAda) 
                ? 'Sudah ada di KBM' 
                : 'Kelas ini belum ada di KBM';

            // Hitung jumlah siswa hadir
            $item->jumlah_hadir = DB::table('absensi_siswa')
                ->where('id_kelas', $item->id)
                ->whereDate('tanggal', $hariIni)
                ->where('id_status_hadir', 1) // Misalnya, 1 = Hadir
                ->count();

            // Hitung total siswa di kelas
            $totalSiswa = DB::table('siswa')
                ->where('id_kelas', $item->id)
                ->count();

            // Hitung jumlah siswa yang belum hadir
            $item->jumlah_belum_hadir = $totalSiswa - $item->jumlah_hadir;

            // Total siswa
            $item->total_siswa = $totalSiswa;

            // Update total kelas hadir dan jumlah hadir/belum hadir
            if ($item->jumlah_hadir > 0) {
                $totalKelasHadir++;
            }

            $totalJumlahHadir += $item->jumlah_hadir;
            $totalJumlahBelumHadir += $item->jumlah_belum_hadir;

            return $item;
        });

        // Ambil kelas pengguna login jika perannya adalah siswa
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
            'userKelas' => $userKelas, // Tambahkan variabel kelas pengguna login
        ]);
    }

    public function laporan()
    {
        return view('laporan');
    }
}
