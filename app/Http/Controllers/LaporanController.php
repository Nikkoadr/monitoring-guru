<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class LaporanController extends Controller
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
    // public function printLaporanIndividu(Request $request)
    // {
    //     $id = $request->id;
    //     $bulan = $request->bulan;
    //     $tahun = $request->tahun;
    //     $user = DB::table('users')
    //         ->where('id', $id)
    //         ->first();
    //     $rekap = DB::table('absensi')
    //         ->join('users', 'absensi.id_user', '=', 'users.id')
    //         ->where('id_user', $user->id)
    //         ->whereMonth('tanggal_absen', $bulan)
    //         ->whereYear('tanggal_absen', $tahun)
    //         ->orderBy('tanggal_absen')
    //         ->get();

    //     return view('layouts.component.printLaporanIndividu', compact('user', 'bulan', 'tahun', 'rekap'));
    // }

    public function index(Request $request)
        {
            $bulan = $request->input('bulan', now()->month);
            $tahun = $request->input('tahun', now()->year);
            return view('laporan.data_laporan', compact('bulan', 'tahun'));
        }
public function print_laporan_bulanan_pendidik(Request $request)
{
    $tanggalAwal = $request->tanggal_awal;
    $tanggalAkhir = $request->tanggal_akhir;

    $selectStatements = [];
    $tanggalMulai = Carbon::parse($tanggalAwal);
    $tanggalSelesai = Carbon::parse($tanggalAkhir);

    while ($tanggalMulai->lte($tanggalSelesai)) {
        $hari = $tanggalMulai->day;
        $selectStatements[] = "MAX(CASE WHEN DAY(absensi_pendidik.tanggal) = $hari THEN CONCAT(absensi_pendidik.jam_masuk, '-', IFNULL(absensi_pendidik.jam_keluar, '00:00:00')) ELSE '' END) as tgl_$hari";
        $tanggalMulai->addDay();
    }

    // Query untuk Guru
    $queryGuru = DB::table('guru')
        ->selectRaw('
            users.name as nama,
            guru.id as id_pendidik,
            "Guru" as jenis_pengguna,
            (CASE 
                WHEN kepsek.id IS NOT NULL THEN "Kepala Sekolah"
                WHEN waka.id IS NOT NULL THEN waka.jabatan
                ELSE "Guru" 
            END) as jabatan,
            ' . implode(', ', $selectStatements))
        ->leftJoin('users', 'guru.id_user', '=', 'users.id')
        ->leftJoin('waka', 'guru.id', '=', 'waka.id_guru')
        ->leftJoin('kepsek', 'guru.id', '=', 'kepsek.id_guru')
        ->leftJoin('absensi_pendidik', function ($join) use ($tanggalAwal, $tanggalAkhir) {
            $join->on('guru.id', '=', 'absensi_pendidik.id_guru')
                ->whereBetween('absensi_pendidik.tanggal', [$tanggalAwal, $tanggalAkhir]);
        })
        ->groupByRaw('guru.id, users.name, kepsek.id, waka.id');

    // Query untuk Karyawan
    $queryKaryawan = DB::table('karyawan')
        ->selectRaw('
            users.name as nama,
            karyawan.id as id_pendidik,
            "Karyawan" as jenis_pengguna,
            (CASE 
                WHEN karyawan.tugas IS NOT NULL THEN karyawan.tugas
                ELSE "Karyawan"
            END) as jabatan,
            ' . implode(', ', $selectStatements))
        ->leftJoin('users', 'karyawan.id_user', '=', 'users.id')
        ->leftJoin('absensi_pendidik', function ($join) use ($tanggalAwal, $tanggalAkhir) {
            $join->on('karyawan.id', '=', 'absensi_pendidik.id_karyawan')
                ->whereBetween('absensi_pendidik.tanggal', [$tanggalAwal, $tanggalAkhir]);
        })
        ->groupByRaw('karyawan.id, users.name, karyawan.tugas');

    // Gabungkan hasil query
    $rekap = $queryGuru->unionAll($queryKaryawan)
        ->orderBy('nama')
        ->get();

    // Proses keterangan per hari
    foreach ($rekap as $data) {
        $keteranganPerHari = [];
        for ($i = 1; $i <= 31; $i++) {
            $key = "tgl_$i";
            $tanggal = Carbon::createFromFormat('Y-m-d', $tanggalAwal)->startOfMonth()->addDays($i - 1);
            if (isset($data->$key) && $data->$key !== '') {
                if ($data->jabatan === 'Guru') {
                    $adaKbm = DB::table('kbm')
                        ->where('id_guru', $data->id_pendidik)
                        ->whereDate('tanggal', $tanggal->format('Y-m-d'))
                        ->exists();
                    $keteranganPerHari[$key] = $adaKbm ? 'Masuk Kelas' : 'Masuk tapi tidak masuk kelas';
                } else {
                    $keteranganPerHari[$key] = 'Hadir';
                }
            }
        }
        $data->keterangan_perhari = $keteranganPerHari;
    }
    //dd($rekap);
    return view('laporan.print_laporan_bulanan_pendidik', compact('tanggalAwal', 'tanggalAkhir', 'rekap'));
}


// public function downloadLaporanBulanan(Request $request)
// {
//     $tanggalAwal = $request->tanggal_awal;
//     $tanggalAkhir = $request->tanggal_akhir;

//     $selectStatements = [];
//     $tanggalMulai = Carbon::parse($tanggalAwal);
//     $tanggalSelesai = Carbon::parse($tanggalAkhir);

//     while ($tanggalMulai->lte($tanggalSelesai)) {
//         $hari = $tanggalMulai->day;
//         $selectStatements[] = "MAX(CASE WHEN DAY(tanggal_absen) = $hari THEN CONCAT(jam_masuk, '-', IFNULL(jam_keluar, '00:00:00')) ELSE '' END) as tgl_$hari";
//         $tanggalMulai->addDay();
//     }

//     $rekap = DB::table('users')
//         ->selectRaw('users.id as id_user, users.jam_kerja, users.nama, users.jabatan, ' . implode(', ', $selectStatements))
//         ->leftJoin('absensi', function($join) use ($tanggalAwal, $tanggalAkhir) {
//             $join->on('users.id', '=', 'absensi.id_user')
//                 ->whereBetween('tanggal_absen', [$tanggalAwal, $tanggalAkhir]);
//         })
//         ->groupByRaw('users.id, users.jam_kerja, users.nama, users.jabatan')
//         ->orderBy('users.nama')
//         ->get();

//     foreach ($rekap as $data) {
//         $totalJamTerlambat = 0;
//         for ($i = 1; $i <= 31; $i++) {
//             $key = "tgl_$i";
//             if (isset($data->$key) && $data->$key !== '') {
//                 $jamMasuk = substr($data->$key, 0, 5);
//                 $jamKerja = $data->jam_kerja;

//                 if ($jamMasuk > $jamKerja) {
//                     $terlambat = Carbon::parse($jamMasuk)->diffInMinutes(Carbon::parse($jamKerja));
//                     $totalJamTerlambat += $terlambat / 60;
//                 }
//             }
//         }
//         $data->total_jam_terlambat = $totalJamTerlambat;
//     }

//     return view('layouts.component.downloadSemuaLaporan', compact('tanggalAwal', 'tanggalAkhir', 'rekap'));
// }

}