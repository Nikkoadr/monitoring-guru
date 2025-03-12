<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
        $id_user_login = Auth::user()->id;
        $kelas = DB::table('kelas')
            ->get();

        // Periksa apakah user adalah admin, waka, atau kepsek
        $isWakaOrKepsek = DB::table('waka')
            ->join('guru', 'waka.id_guru', '=', 'guru.id')
            ->where('guru.id_user', $id_user_login)
            ->exists() 
            || DB::table('kepsek')
            ->join('guru', 'kepsek.id_guru', '=', 'guru.id')
            ->where('guru.id_user', $id_user_login)
            ->exists();

        // Periksa apakah user adalah wali kelas
        $isWaliKelas = DB::table('walas') // Asumsi tabel wali_kelas
            ->join('guru', 'walas.id_guru', '=', 'guru.id')
            ->where('guru.id_user', $id_user_login)
            ->exists();

        // Periksa apakah user adalah guru biasa
        $isGuruBiasa = DB::table('guru')
            ->where('id_user', $id_user_login)
            ->exists();

        // Logika akses
        if (Gate::allows('admin') || $isWakaOrKepsek) {
            // Admin, Waka, atau Kepsek: lanjutkan akses
            $bulan = $request->input('bulan', now()->month);
            $tahun = $request->input('tahun', now()->year);

            return view('laporan.data_laporan', compact('bulan', 'tahun','kelas'));
        } elseif ($isWaliKelas) {
            // Jika wali kelas: tampilkan laporan kelasnya
            $kelas = DB::table('walas')
                ->join('kelas', 'walas.id_kelas', '=', 'kelas.id')
                ->where('walas.id_guru', function ($query) use ($id_user_login) {
                    $query->select('id')
                        ->from('guru')
                        ->where('id_user', $id_user_login)
                        ->limit(1);
                })
                ->select('kelas.nama_kelas')
                ->first();

            $bulan = $request->input('bulan', now()->month);
            $tahun = $request->input('tahun', now()->year);

            return view('laporan.print_laporan_bulanan_kelas', compact('kelas', 'bulan', 'tahun'));
        } elseif ($isGuruBiasa) {
            // Jika guru biasa: redirect ke home dengan pesan error
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke laporan.');
        } else {
            // Jika bukan admin, guru, atau tidak teridentifikasi
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }
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
            $selectStatements[] = "
                MAX(CASE 
                    WHEN DAY(absensi_pendidik.tanggal) = $hari 
                    THEN 
                        CASE 
                            WHEN absensi_pendidik.id_status_hadir = 1 THEN 'H'
                            WHEN absensi_pendidik.id_status_hadir = 2 THEN 'A'
                            WHEN absensi_pendidik.id_status_hadir = 3 THEN 'I'
                            WHEN absensi_pendidik.id_status_hadir = 4 THEN 'S'
                            WHEN absensi_pendidik.id_status_hadir = 5 THEN 'B'
                            ELSE '?'
                        END
                    ELSE '' 
                END) as tgl_$hari";
            $tanggalMulai->addDay();
        }

        $queryGuru = DB::table('guru')
            ->selectRaw('
                users.name as nama,
                guru.id as id_pendidik,
                "Guru" as jenis_pengguna,
                COALESCE(jabatan.nama_jabatan, "Guru") as jabatan,
                ' . implode(', ', $selectStatements))
            ->leftJoin('users', 'guru.id_user', '=', 'users.id')
            ->leftJoin('waka', 'guru.id', '=', 'waka.id_guru')
            ->leftJoin('jabatan', 'waka.id_jabatan', '=', 'jabatan.id')
            ->leftJoin('kepsek', 'guru.id', '=', 'kepsek.id_guru')
            ->leftJoin('absensi_pendidik', function ($join) use ($tanggalAwal, $tanggalAkhir) {
                $join->on('guru.id', '=', 'absensi_pendidik.id_guru')
                    ->whereBetween('absensi_pendidik.tanggal', [$tanggalAwal, $tanggalAkhir]);
            })
            ->groupByRaw('guru.id, users.name, jabatan.nama_jabatan, kepsek.id, waka.id');

        $queryKaryawan = DB::table('karyawan')
            ->selectRaw('
                users.name as nama,
                karyawan.id as id_pendidik,
                "Karyawan" as jenis_pengguna,
                COALESCE(karyawan.tugas, "Karyawan") as jabatan,
                ' . implode(', ', $selectStatements))
            ->leftJoin('users', 'karyawan.id_user', '=', 'users.id')
            ->leftJoin('absensi_pendidik', function ($join) use ($tanggalAwal, $tanggalAkhir) {
                $join->on('karyawan.id', '=', 'absensi_pendidik.id_karyawan')
                    ->whereBetween('absensi_pendidik.tanggal', [$tanggalAwal, $tanggalAkhir]);
            })
            ->groupByRaw('karyawan.id, users.name, karyawan.tugas');

        $rekap = $queryGuru->unionAll($queryKaryawan)
            ->orderBy('nama')
            ->get();

        $filter_rekap = $rekap->map(function ($data) use ($tanggalAwal) {
            $keteranganPerHari = [];
            $totalHadir = $totalAlpha = $totalIzin = $totalSakit = $totalBolos = $totalTidakDiKBM = 0;

            for ($i = 1; $i <= 31; $i++) {
                $key = "tgl_$i";
                $tanggal = Carbon::createFromFormat('Y-m-d', $tanggalAwal)->startOfMonth()->addDays($i - 1);

                if (isset($data->$key) && $data->$key !== '') {
                    $statusKehadiran = $data->$key;

                    if ($data->jenis_pengguna === 'Guru' && $data->jabatan === 'Guru') {
                        $adaKbm = DB::table('kbm')
                            ->where('id_guru', $data->id_pendidik)
                            ->whereDate('tanggal', $tanggal->format('Y-m-d'))
                            ->exists();

                        if ($data->$key === 'H') {
                            $statusKehadiran = $adaKbm ? 'H' : '?';
                        }
                    }

                    $keteranganPerHari[$key] = $statusKehadiran;
                    match ($statusKehadiran) {
                        'H' => $totalHadir++,
                        'A' => $totalAlpha++,
                        'I' => $totalIzin++,
                        'S' => $totalSakit++,
                        'B' => $totalBolos++,
                        '?' => $totalTidakDiKBM++,
                        default => null,
                    };
                }
            }

            return (object) [
                'nama' => $data->nama,
                'id_pendidik' => $data->id_pendidik,
                'jenis_pengguna' => $data->jenis_pengguna,
                'jabatan' => $data->jabatan,
                'keterangan_perhari' => $keteranganPerHari,
                'total_hadir' => $totalHadir,
                'total_alpha' => $totalAlpha,
                'total_izin' => $totalIzin,
                'total_sakit' => $totalSakit,
                'total_bolos' => $totalBolos,
                'total_tidak_di_kbm' => $totalTidakDiKBM,
            ];
        });

        return view('laporan.print_laporan_bulanan_pendidik', [
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
            'rekap' => $filter_rekap,
        ]);
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
    public function print_laporan_bulanan_kelas(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $idKelas = $request->id_kelas; // Ambil ID kelas dari request

        $selectStatements = [];
        $tanggalMulai = Carbon::parse($tanggalAwal);
        $tanggalSelesai = Carbon::parse($tanggalAkhir);

        // Loop untuk membuat kolom per tanggal
        while ($tanggalMulai->lte($tanggalSelesai)) {
            $hari = $tanggalMulai->day;
            $selectStatements[] = "
                COALESCE(
                    MAX(CASE 
                        WHEN DAY(absensi_siswa.tanggal) = $hari 
                        THEN 
                            CASE 
                                WHEN absensi_siswa.id_status_hadir = 1 THEN 'H'
                                WHEN absensi_siswa.id_status_hadir = 2 THEN 'A'
                                WHEN absensi_siswa.id_status_hadir = 3 THEN 'I'
                                WHEN absensi_siswa.id_status_hadir = 4 THEN 'S'
                                ELSE '?'
                            END
                        ELSE NULL 
                    END), '-') as tgl_$hari";
            $tanggalMulai->addDay();
        }

        // Query Data Absensi Siswa
        $rekapQuery = DB::table('siswa')
            ->selectRaw('
                users.name as nama_siswa,
                siswa.id as id_siswa,
                kelas.nama_kelas,
                ' . implode(', ', $selectStatements))
            ->leftJoin('users', 'siswa.id_user', '=', 'users.id')
            ->leftJoin('kelas', 'siswa.id_kelas', '=', 'kelas.id')
            ->leftJoin('absensi_siswa', function ($join) use ($tanggalAwal, $tanggalAkhir) {
                $join->on('siswa.id', '=', 'absensi_siswa.id_siswa')
                    ->whereBetween('absensi_siswa.tanggal', [$tanggalAwal, $tanggalAkhir]);
            })
            ->whereNotNull('siswa.id_kelas'); // Hanya siswa yang memiliki kelas

        // Tambahkan filter jika kelas dipilih
        if (!empty($idKelas)) {
            $rekapQuery->where('siswa.id_kelas', $idKelas);
        }

        $rekap = $rekapQuery
            ->groupByRaw('siswa.id, users.name, kelas.nama_kelas')
            ->orderBy('kelas.nama_kelas')
            ->orderBy('nama_siswa')
            ->get();

        // Proses data untuk perhitungan total
        $filter_rekap = $rekap->map(function ($data) use ($tanggalAwal) {
            $keteranganPerHari = [];
            $totalHadir = 0;
            $totalAlpha = 0;
            $totalIzin = 0;
            $totalSakit = 0;
            $totalTidakDiketahui = 0;

            for ($i = 1; $i <= 31; $i++) {
                $key = "tgl_$i";
                $statusKehadiran = $data->$key ?? '-';

                $keteranganPerHari[$key] = $statusKehadiran;

                match ($statusKehadiran) {
                    'H' => $totalHadir++,
                    'A' => $totalAlpha++,
                    'I' => $totalIzin++,
                    'S' => $totalSakit++,
                    '?' => $totalTidakDiketahui++,
                    default => null,
                };
            }

            return (object) [
                'nama_siswa' => $data->nama_siswa,
                'nama_kelas' => $data->nama_kelas,
                'keterangan_perhari' => $keteranganPerHari,
                'total_hadir' => $totalHadir,
                'total_alpha' => $totalAlpha,
                'total_izin' => $totalIzin,
                'total_sakit' => $totalSakit,
                'total_tidak_diketahui' => $totalTidakDiketahui,
            ];
        });

        return view('laporan.print_laporan_bulanan_kelas', [
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
            'rekap' => $filter_rekap,
        ]);
    }

}