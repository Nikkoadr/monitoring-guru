<?php

namespace App\Http\Controllers;

use App\Models\Absensi_siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use App\Models\Setting;

class AbsensiController extends Controller
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
    public function presensi_siswa($id){
        $user = Auth::user();
        $isKmKelas = DB::table('ketua_kelas')
        ->join('siswa', 'ketua_kelas.id_siswa', '=', 'siswa.id')
        ->where('siswa.id_user', $user->id)
        ->exists();
        
        if (Gate::allows('siswa') || $isKmKelas) {
            $user = Auth::user();
            $data_kbm = DB::table('kbm')->where('id', $id)->first();
            $kelas = DB::table('kelas')->where('id', $data_kbm->id_kelas)->first();
            $guru = DB::table('guru')
                ->join('users', 'guru.id_user', '=', 'users.id')
                ->where('guru.id', $data_kbm->id_guru)
                ->select('users.name as nama_guru', 'guru.*')
                ->first();
            $mapel = DB::table('mapel')->where('id', $data_kbm->id_mapel)->first();
            return view('absensi.presensi_siswa', compact('user','data_kbm', 'kelas', 'guru', 'mapel'));
        }else{
            return redirect('/home')->with('error', 'Anda Tidak Memiliki Akses');
        }
    }

    public function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    public function tambah_presensi(Request $request)
    {
        $userId = Auth::user()->id;
        $siswa = DB::table('siswa')->where('id_user', $userId)->first();
        $tanggal_sekarang = Carbon::now()->format('Y-m-d');
        $jam_sekarang = Carbon::now()->format('H:i');

        // Cek apakah siswa sudah absen pada id_kbm ini
        $absensiExists = DB::table('absensi_siswa')
            ->where('id_siswa', $siswa->id)
            ->where('id_kbm', $request->id_kbm)
            ->exists();

        if ($absensiExists) {
            return redirect('/kbm')->with('error', 'Anda sudah melakukan absensi pada mata pelajaran ini.');
        }

        [$latitudeUser, $longitudeUser] = explode(",", $request->lokasi);
        $radius = round($this->distance(
            -6.362998, 108.113522, 
            $latitudeUser, $longitudeUser
        )["meters"]);

        if ($radius > 100) {
            return redirect('/kbm')->with('error', 'Jarak Anda terlalu jauh dari Sekolah');
        } else {
            $data = [
                'tanggal' => $tanggal_sekarang,
                'id_siswa' => $siswa->id,
                'id_kbm' => $request->id_kbm,
                'id_kelas' => $request->id_kelas,
                'id_status_hadir' => 1,
                'jam_hadir' => $jam_sekarang,
                'lokasi' => $request->lokasi,
            ];

            if ($request->has('foto')) {
                $foto_base64 = base64_decode(explode("base64,", $request->foto)[1]);
                $nama_foto = uniqid() . '.png';
                Storage::disk(env('STORAGE_DISK'))->put('foto_absensi_siswa/' . $nama_foto, $foto_base64);
                $data['foto'] = $nama_foto;
            }

            DB::table('absensi_siswa')->insert($data);
        }

        return redirect('/kbm')->with('success', 'Presensi Anda Berhasil');
    }

    public function lihat_presensi_siswa($id)
    {
        $id_kelas = DB::table('kbm')
            ->where('id', $id)
            ->value('id_kelas');

        $status_hadir = DB::table('status_hadir')
            ->get();

        $data_absensi = DB::table('siswa')
            ->join('users', 'siswa.id_user', '=', 'users.id')
            ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id')
            ->leftJoin('absensi_siswa', function ($join) use ($id) {
                $join->on('siswa.id', '=', 'absensi_siswa.id_siswa')
                    ->where('absensi_siswa.id_kbm', $id);
            })
            ->leftJoin('status_hadir', 'absensi_siswa.id_status_hadir', '=', 'status_hadir.id')
            ->where('siswa.id_kelas', $id_kelas)
            ->select(
                'absensi_siswa.*',
                'siswa.id as id_siswa',
                'users.name as nama_siswa',
                'kelas.nama_kelas',
                DB::raw('COALESCE(status_hadir.nama_status_hadir, "Alfa") as status_hadir'),
                DB::raw('COALESCE(absensi_siswa.foto, "default.jpeg") as foto'),
                DB::raw('COALESCE(absensi_siswa.jam_hadir, "00:00") as jam_hadir')
            )
            ->get();
        return view('absensi.lihat_presensi_siswa', compact('data_absensi', 'id_kelas', 'id', 'status_hadir'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id_kbm' => 'required|integer',
            'id_kelas' => 'required|integer',
            'id_siswa' => 'required|integer',
            'id_status_hadir' => 'required|integer',
        ]);

        Absensi_siswa::updateOrCreate(
            [
                'id_kbm' => $request->id_kbm,
                'id_siswa' => $request->id_siswa,
            ],
            [
                'id_kelas' => $request->id_kelas,
                'id_status_hadir' => $request->id_status_hadir,
                'jam_hadir' => now()->format('H:i'),
                'tanggal' => now()->format('Y-m-d'),
            ]
        );

        $status_hadir = DB::table('status_hadir')
            ->where('id', $request->id_status_hadir)
            ->value('nama_status_hadir');

        return redirect('/lihat_presensi_siswa_' . $request->id_kbm)->with('success', 'Status Hadir ' . $status_hadir . ' Berhasil Diubah');
    }

public function presensi_pendidik()
{
    $tanggal = date("Y-m-d");
    $user = Auth::user();

    // Mengecek apakah user adalah guru atau karyawan
    $guru = DB::table('guru')->where('id_user', $user->id)->first();
    $karyawan = DB::table('karyawan')->where('id_user', $user->id)->first();

    $cek = null; // Variabel untuk mengecek presensi

    // Jika yang login adalah guru
    if ($guru) {
        $cek = DB::table('absensi_pendidik')->where('tanggal', $tanggal)->where('id_guru', $guru->id)->count();
    }
    // Jika yang login adalah karyawan
    elseif ($karyawan) {
        $cek = DB::table('absensi_pendidik')->where('tanggal', $tanggal)->where('id_karyawan', $karyawan->id)->count();
    }

    $setting = Setting::first();
    $limit_presensi = $setting->limit_presensi;
    $jam = date("H:i:s");

    return view('absensi.presensi_pendidik', compact('cek', 'setting', 'jam', 'limit_presensi'));
}

    public function post_presensi_pendidik(Request $request)
    {
        // Validasi input
        $request->validate([
            'foto' => 'required|string',
            'lokasi' => 'required|string',
        ]);

        $user = Auth::user();
        $hari_ini = now()->toDateString();
        $jam = Carbon::now();

        // Ambil data guru atau karyawan
        $guru = DB::table('guru')->where('id_user', $user->id)->first();
        $karyawan = DB::table('karyawan')->where('id_user', $user->id)->first();

        // Tentukan ID guru atau karyawan
        $idGuru = $guru ? $guru->id : null;
        $idKaryawan = $karyawan ? $karyawan->id : null;

        if (!$idGuru && !$idKaryawan) {
            return response()->json(['status' => 'error', 'message' => 'User tidak terdaftar sebagai guru atau karyawan.']);
        }

        // Ambil pengaturan lokasi
        $setting = Setting::first();
        [$latUser, $lngUser] = explode(",", $request->lokasi);

        // Hitung jarak dan cek jika berada dalam radius yang ditentukan
        $radius = round($this->distance($setting->lokasi_latitude, $setting->lokasi_longitude, $latUser, $lngUser)["meters"]);
        if ($radius > $setting->radius_lokasi) {
            return response()->json(['status' => 'error', 'message' => "Maaf, Jarak Anda $radius M dari {$setting->nama_aplikasi}"]);
        }

        // Cek apakah sudah absen
        $cekPresensi = DB::table('absensi_pendidik')
            ->where('tanggal', $hari_ini)
            ->where(function ($query) use ($idGuru, $idKaryawan) {
                $query->where('id_guru', $idGuru)
                    ->orWhere('id_karyawan', $idKaryawan);
            })
            ->first();

        // Tentukan foto path dan decode foto
        $fotoPath = "{$user->id}-$hari_ini-" . ($cekPresensi ? "keluar" : "masuk") . ".png";
        $fotoBase64 = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->foto));

        if ($cekPresensi) {
            // Jika sudah absen keluar
            if ($cekPresensi->jam_keluar) {
                return response()->json(['status' => 'error', 'message' => 'Anda sudah absen Pulang hari ini.']);
            }

            // Cek apakah bisa absen keluar
            $selisih = strtotime($jam) - strtotime($cekPresensi->jam_masuk);
            if ($selisih < 300) {
                return response()->json(['status' => 'error', 'message' => 'Anda tidak bisa absen keluar terlalu cepat setelah absen masuk! Tunggu 5 menit.']);
            }

            // Update data presensi keluar
            DB::table('absensi_pendidik')->where('id', $cekPresensi->id)->update([
                'jam_keluar' => $jam,
                'foto_keluar' => $fotoPath,
                'lokasi_keluar' => $request->lokasi,
            ]);
        } else {
            // Insert data presensi masuk
            DB::table('absensi_pendidik')->insert([
                'id_status_hadir' => 1,
                'id_guru' => $idGuru,
                'id_karyawan' => $idKaryawan,
                'tanggal' => $hari_ini,
                'jam_masuk' => $jam,
                'foto_masuk' => $fotoPath,
                'lokasi_masuk' => $request->lokasi,
            ]);
        }

        // Simpan foto presensi
        Storage::disk(env('STORAGE_DISK'))->put('foto_presensi_pendidik/' . $fotoPath, $fotoBase64);

        // Kembalikan response
        $message = $cekPresensi ? 'Anda Sudah Absen Pulang. Hati-hati di jalan!' : 'Terima kasih Anda sudah melakukan absen masuk. Jangan lupa presensi keluar!';
        return response()->json(['status' => 'success', 'message' => $message]);
    }

}
