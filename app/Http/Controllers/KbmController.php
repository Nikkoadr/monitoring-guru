<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Carbon;
use App\Models\Kbm;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;

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
    } elseif ($user->id_role == 4 || $user->id_role == 5) {
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

    public function form_tambah_kbm() {
        $user = Auth::user();
        $tanggal_sekarang = Carbon::now()->format('Y-m-d'); // Mendapatkan tanggal hari ini
        $jam_sekarang = Carbon::now()->format('H:i');   // Mendapatkan waktu (jam dan menit) sekarang

        if (Gate::allows('km_kelas')) {
            $data_kelas = DB::table('kelas')
                ->join('siswa', 'kelas.id', '=', 'siswa.id_kelas')
                ->select('kelas.*')
                ->where('siswa.id_user', $user->id)
                ->first();

            $data_mapel = DB::table('mapel')->get();

            return view('kbm.form_tambah_kbm', compact('user', 'data_kelas','data_mapel', 'tanggal_sekarang', 'jam_sekarang'));

        } elseif (Gate::allows('admin')) {
            $data_kelas = DB::table('kelas')->get();
            $data_mapel = DB::table('mapel')->get();

            return view('kbm.form_tambah_kbm', compact('user', 'data_kelas', 'data_mapel', 'tanggal_sekarang', 'jam_sekarang'));
        }
    }

    public function get_guru($id_mapel) {
        $guru = DB::table('guru_mapel')
            ->join('guru', 'guru_mapel.id_guru', '=', 'guru.id')
            ->join('users', 'guru.id_user', '=', 'users.id')  // Join ke tabel users untuk mendapatkan nama guru
            ->select('guru.id', 'users.name as nama_guru')     // Ambil nama dari tabel users
            ->where('guru_mapel.id_mapel', $id_mapel)
            ->get();

        return response()->json($guru);
    }

public function tambah_kbm(Request $request) {
    $request->validate([
        'jam_masuk' => 'required',
        'tanggal' => 'required',
        'id_mapel' => 'required',
        'id_guru' => 'required',
        'id_kelas' => 'required',
        'jam_ke' => 'required',
        'foto_masuk' => 'required',
        'keterangan' => 'required',
    ]);

    // Buat instance baru untuk KBM
    $kbm = new Kbm();
    $kbm->tanggal = $request->tanggal;
    $kbm->jam_masuk = $request->jam_masuk;
    $kbm->jam_ke = $request->jam_ke;
    $kbm->id_mapel = $request->id_mapel;
    $kbm->id_guru = $request->id_guru;
    $kbm->id_kelas = $request->id_kelas;
    $kbm->keterangan = $request->keterangan;

    // Proses penyimpanan foto masuk
    if ($request->has('foto_masuk')) {
        // Decode base64
        $foto_base64 = base64_decode(explode("base64,", $request->foto_masuk)[1]);

        // Nama unik untuk file foto
        $nama_foto = uniqid() . '.png';

        // Simpan ke folder public/storage/foto_masuk_kbm
        Storage::disk(env('STORAGE_DISK'))->put('foto_masuk_kbm/' . $nama_foto, $foto_base64);

        // Simpan nama file ke database
        $kbm->foto_masuk = $nama_foto;
    }

    // Simpan data KBM
    $kbm->save();

    // Redirect dengan pesan sukses
    return redirect('/kbm')->with('success', 'Data KBM berhasil ditambahkan');
}
public function form_selesai_kbm($id) {
    // Ambil data KBM berdasarkan ID
    $kbm = Kbm::findOrFail($id);
    
    // Ambil jam saat ini
    $jam_sekarang = Carbon::now()->format('H:i');
    
    // Kirim data ke view
    return view('kbm.form_selesai_kbm', compact('kbm', 'jam_sekarang', 'id'));
}
public function update_selesai_kbm(Request $request, $id) {
    $request->validate([
        'jam_keluar' => 'required',
        'foto_keluar' => 'required',
    ]);
    $kbm = Kbm::findOrFail($id);
    $kbm->jam_keluar = $request->jam_keluar;
    if ($request->has('foto_keluar')) {
        $foto_base64 = base64_decode(explode("base64,", $request->foto_keluar)[1]);
        $nama_foto = uniqid() . '.png';
        Storage::disk(env('STORAGE_DISK'))->put('foto_keluar_kbm/' . $nama_foto, $foto_base64);
        $kbm->foto_keluar = $nama_foto;
    }
    $kbm->save();
    return redirect('/kbm')->with('success', 'Data KBM berhasil diperbarui');
}


    public function hapus_kbm($id) {
        $kbm = Kbm::find($id);
        $kbm->delete();
        return redirect('/kbm')->with('success', 'Data KBM berhasil dihapus'); 
    }
}