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

        // Pastikan lokasi dikirim
        if (!$request->lokasi) {
            return redirect('/kbm')->with('error', 'Lokasi harus diaktifkan untuk melakukan presensi.');
        }

        // Pastikan foto dikirim
        if (!$request->has('foto') || empty($request->foto)) {
            return redirect('/kbm')->with('error', 'Foto harus diunggah untuk melakukan presensi.');
        }

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
            return redirect('/kbm')->with('error', 'Jarak Anda terlalu jauh dari Sekolah.');
        } else {
            // Simpan data absensi
            $foto_base64 = base64_decode(explode("base64,", $request->foto)[1]);
            $nama_foto = uniqid() . '.png';
            Storage::disk(env('STORAGE_DISK'))->put('foto_absensi_siswa/' . $nama_foto, $foto_base64);

            DB::table('absensi_siswa')->insert([
                'tanggal' => $tanggal_sekarang,
                'id_siswa' => $siswa->id,
                'id_kbm' => $request->id_kbm,
                'id_kelas' => $request->id_kelas,
                'id_status_hadir' => 1,
                'jam_hadir' => $jam_sekarang,
                'lokasi' => $request->lokasi,
                'foto' => $nama_foto,
            ]);
        }

        return redirect('/kbm')->with('success', 'Presensi Anda Berhasil.');
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
                DB::raw('COALESCE(absensi_siswa.foto, "default.jpg") as foto'),
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

    public function simpanDatasetWajah(Request $request) {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User tidak ditemukan'], 403);
        }

        // Validasi request
        $request->validate([
            'dataset_wajah' => 'required|string',
        ]);

        // Cek apakah dataset_wajah adalah JSON valid
        $dataset = json_decode($request->dataset_wajah, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['status' => 'error', 'message' => 'Dataset wajah tidak valid'], 400);
        }

        // Simpan dataset_wajah ke database
        $user->dataset_wajah = json_encode($dataset);
        if ($user->save()) {
            return response()->json(['status' => 'success', 'message' => 'Dataset wajah berhasil disimpan!']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan dataset wajah.']);
        }
    }

    public function ambilDatasetWajah() {
        $user = Auth::user();

        if (!$user || empty($user->dataset_wajah)) {
            return response()->json(['status' => 'error', 'message' => 'Dataset wajah tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $user->dataset_wajah
        ]);
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
    public function data_presensi_pendidik(Request $request)
    {
        // Ambil tanggal filter dari request, default adalah hari ini
        $tanggal = $request->input('tanggal', now()->toDateString());

        // Query data presensi
        $data = DB::table('absensi_pendidik')
            ->leftJoin('guru', 'guru.id', '=', 'absensi_pendidik.id_guru')
            ->leftJoin('users as user_guru', 'user_guru.id', '=', 'guru.id_user')
            ->leftJoin('karyawan', 'karyawan.id', '=', 'absensi_pendidik.id_karyawan')
            ->leftJoin('users as user_karyawan', 'user_karyawan.id', '=', 'karyawan.id_user')
            ->join('status_hadir', 'status_hadir.id', '=', 'absensi_pendidik.id_status_hadir')
            ->select(
                'absensi_pendidik.*',
                DB::raw("CASE 
                    WHEN guru.id IS NOT NULL THEN user_guru.name
                    WHEN karyawan.id IS NOT NULL THEN user_karyawan.name
                    ELSE 'Tidak Diketahui'
                END as nama_pendidik"),
                'status_hadir.nama_status_hadir as status_hadir'
            )
            ->whereDate('absensi_pendidik.tanggal', '=', $tanggal) // Filter berdasarkan tanggal
            ->orderBy('absensi_pendidik.tanggal', 'desc')
            ->orderBy('absensi_pendidik.created_at', 'desc')
            ->get();

        // Kirim data dan tanggal ke view
        return view('absensi.data_presensi_pendidik', compact('data', 'tanggal'));
    }

    public function form_tambah_presensi_pendidik()
    {
        $status_hadir = DB::table('status_hadir')->get();
        return view('absensi.form_tambah_presensi_pendidik', compact('status_hadir'));
    }

public function admin_post_presensi_pendidik(Request $request)
    {
    // Validasi input
    $request->validate([
        'id_pendidik' => 'required|integer',
        'id_status_hadir' => 'required|integer',
        'tanggal' => 'required|date',
        'jam_masuk' => 'nullable|date_format:H:i',
        'jam_keluar' => 'nullable|date_format:H:i|after_or_equal:jam_masuk',
        'foto_masuk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
    ]);

    // Cek apakah pendidik adalah guru atau karyawan
    $cek_guru = DB::table('guru')->where('id_user', $request->id_pendidik)->first();
    $cek_karyawan = DB::table('karyawan')->where('id_user', $request->id_pendidik)->first();

    // Inisialisasi variabel untuk nama file
    $fileName = null;

    // Jika ada file yang diunggah
    if ($request->hasFile('foto_masuk')) {
        $file = $request->file('foto_masuk');
        $fileName = time() . '_' . $file->getClientOriginalName();
        Storage::disk(env('STORAGE_DISK', 'public'))->put('foto_presensi_pendidik/' . $fileName, file_get_contents($file));
    }

    if ($cek_guru) {
        // Jika pendidik adalah guru
        DB::table('absensi_pendidik')->insert([
            'id_status_hadir' => $request->id_status_hadir,
            'id_guru' => $cek_guru->id,
            'id_karyawan' => null,
            'tanggal' => $request->tanggal,
            'jam_masuk' => $request->jam_masuk,
            'jam_keluar' => $request->jam_keluar,
            'foto_masuk' => $fileName,
        ]);
        return redirect('/data_presensi_pendidik')->with('success', 'Presensi berhasil ditambahkan untuk Guru.');
    } elseif ($cek_karyawan) {
        // Jika pendidik adalah karyawan
        DB::table('absensi_pendidik')->insert([
            'id_status_hadir' => $request->id_status_hadir,
            'id_guru' => null,
            'id_karyawan' => $cek_karyawan->id,
            'tanggal' => $request->tanggal,
            'jam_masuk' => $request->jam_masuk,
            'jam_keluar' => $request->jam_keluar,
            'foto_masuk' => $fileName,
        ]);
        return redirect('/data_presensi_pendidik')->with('success', 'Presensi berhasil ditambahkan untuk Karyawan.');
    } else {
        // Jika tidak ditemukan
        return redirect('/data_presensi_pendidik')->with('error', 'Data pendidik tidak ditemukan.');
    }
    }
    public function form_edit_presensi_pendidik($id)
    {
        $data = DB::table('absensi_pendidik')
            ->leftJoin('guru', 'guru.id', '=', 'absensi_pendidik.id_guru')
            ->leftJoin('users as guru_users', 'guru.id_user', '=', 'guru_users.id') // Relasi ke tabel users untuk guru
            ->leftJoin('karyawan', 'karyawan.id', '=', 'absensi_pendidik.id_karyawan')
            ->leftJoin('users as karyawan_users', 'karyawan.id_user', '=', 'karyawan_users.id') // Relasi ke tabel users untuk karyawan
            ->join('status_hadir', 'status_hadir.id', '=', 'absensi_pendidik.id_status_hadir')
            ->where('absensi_pendidik.id', $id)
            ->select(
                'absensi_pendidik.*',
                'status_hadir.id as id_status_hadir',
                DB::raw("
                    CASE 
                        WHEN absensi_pendidik.id_guru IS NOT NULL THEN guru_users.name
                        WHEN absensi_pendidik.id_karyawan IS NOT NULL THEN karyawan_users.name
                        ELSE 'Tidak Diketahui'
                    END as nama_pendidik")
            )
            ->first();

        $status_hadir = DB::table('status_hadir')->get();

        // Menentukan ID pendidik yang sesuai
        $id_pendidik = $data->id_guru ? $data->id_guru : $data->id_karyawan;

        return view('absensi.form_edit_presensi_pendidik', compact('data', 'status_hadir', 'id_pendidik'));
    }

    public function update_presensi_pendidik(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'id_status_hadir' => 'required|integer',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable',
            'jam_keluar' => 'nullable|after_or_equal:jam_masuk',
            'foto_masuk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_keluar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Ambil data absensi dari database
        $absensi = DB::table('absensi_pendidik')->where('id', $id)->first();
        if (!$absensi) {
            return redirect('/data_presensi_pendidik')->with('error', 'Data presensi tidak ditemukan.');
        }

        // Default gunakan foto lama
        $fileMasuk = $absensi->foto_masuk;
        $fileKeluar = $absensi->foto_keluar;

        // Proses foto masuk
        if ($request->hasFile('foto_masuk')) {
            $file = $request->file('foto_masuk');
            $fileMasuk = time() . '_masuk_' . $file->getClientOriginalName();
            Storage::disk(env('STORAGE_DISK', 'public'))->put('foto_presensi_pendidik/' . $fileMasuk, file_get_contents($file));

            // Hapus foto masuk lama jika ada
            if ($absensi->foto_masuk) {
                Storage::disk(env('STORAGE_DISK', 'public'))->delete('foto_presensi_pendidik/' . $absensi->foto_masuk);
            }
        }

        // Proses foto keluar
        if ($request->hasFile('foto_keluar')) {
            $file = $request->file('foto_keluar');
            $fileKeluar = time() . '_keluar_' . $file->getClientOriginalName();
            Storage::disk(env('STORAGE_DISK', 'public'))->put('foto_presensi_pendidik/' . $fileKeluar, file_get_contents($file));

            // Hapus foto keluar lama jika ada
            if ($absensi->foto_keluar) {
                Storage::disk(env('STORAGE_DISK', 'public'))->delete('foto_presensi_pendidik/' . $absensi->foto_keluar);
            }
        }

        // Update data presensi
        DB::beginTransaction();
        try {
            DB::table('absensi_pendidik')->where('id', $id)->update([
                'id_status_hadir' => $request->id_status_hadir,
                'tanggal' => $request->tanggal,
                'jam_masuk' => $request->jam_masuk,
                'jam_keluar' => $request->jam_keluar,
                'foto_masuk' => $fileMasuk,
                'foto_keluar' => $fileKeluar,
            ]);

            DB::commit();
            return redirect('/data_presensi_pendidik')->with('success', 'Presensi berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/data_presensi_pendidik')->with('error', 'Terjadi kesalahan saat mengupdate presensi: ' . $e->getMessage());
        }
    }

    public function hapus_presensi_pendidik($id)
    {
        DB::table('absensi_pendidik')->where('id', $id)->delete();
        return redirect('/data_presensi_pendidik')->with('success', 'Presensi berhasil dihapus.');
    }
}