<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Carbon;
use App\Models\Kbm;
use Illuminate\Support\Facades\Storage;

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
    $hari_ini = now()->toDateString();
    if ($user->id_role == 1) {
        $data_kbm = DB::table('kbm')
            ->join('mapel', 'kbm.id_mapel', '=', 'mapel.id')
            ->join('guru', 'kbm.id_guru', '=', 'guru.id')
            ->join('users', 'guru.id_user', '=', 'users.id')
            ->join('kelas', 'kbm.id_kelas', '=', 'kelas.id')
            ->select('kbm.*', 'mapel.nama_mapel', 'users.name as nama_guru', 'kelas.nama_kelas')
            ->whereDate('kbm.tanggal', $hari_ini)
            ->get();
    }elseif ($user->id_role == 3) {
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
                ->where('kbm.id_guru', $guru->id)
                ->whereDate('kbm.tanggal', $hari_ini)
                ->get();
        }
    } elseif ($user->id_role == 4 || $user->id_role == 5) {
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
                ->where('kbm.id_kelas', $siswa->id_kelas)
                ->whereDate('kbm.tanggal', $hari_ini)
                ->get();
        } else {
            $data_kbm = collect();
        }
    } else {
        $data_kbm = collect();
    }

    return view('kbm.data_kbm', compact('data_kbm','user'));
}

    public function form_tambah_kbm() {
        $user = Auth::user();
        $tanggal_sekarang = Carbon::now()->format('Y-m-d');
        $jam_sekarang = Carbon::now()->format('H:i');

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

    public function form_edit_kbm($id) {
        $data_kbm = Kbm::find($id);
        return view('kbm.form_edit_kbm', compact('data_kbm'));
    }

    public function update_kbm(Request $request, $id) {
        $request->validate([
            'tanggal' => 'required',
            'jam_ke' => 'required',
            'jam_masuk' => 'required',
            'jam_keluar' => 'nullable',
            'keterangan' => 'required',
        ]);
        $kbm = Kbm::findOrFail($id);
        $kbm->tanggal = $request->tanggal;
        $kbm->jam_ke = $request->jam_ke;
        $kbm->jam_masuk = $request->jam_masuk;
        $kbm->jam_keluar = $request->jam_keluar;
        $kbm->keterangan = $request->keterangan;
        $kbm->save();
        return redirect('/kbm')->with('success', 'Data KBM berhasil diperbarui');
    }


    public function get_guru($id_mapel) {
        $guru = DB::table('guru_mapel')
            ->join('guru', 'guru_mapel.id_guru', '=', 'guru.id')
            ->join('users', 'guru.id_user', '=', 'users.id')
            ->select('guru.id', 'users.name as nama_guru')
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

    $kbm = new Kbm();
    $kbm->tanggal = $request->tanggal;
    $kbm->jam_masuk = $request->jam_masuk;
    $kbm->jam_ke = $request->jam_ke;
    $kbm->id_mapel = $request->id_mapel;
    $kbm->id_guru = $request->id_guru;
    $kbm->id_kelas = $request->id_kelas;
    $kbm->keterangan = $request->keterangan;

    if ($request->has('foto_masuk')) {
        $foto_base64 = base64_decode(explode("base64,", $request->foto_masuk)[1]);
        $nama_foto = uniqid() . '.png';
        Storage::disk(env('STORAGE_DISK'))->put('foto_masuk_kbm/' . $nama_foto, $foto_base64);
        $kbm->foto_masuk = $nama_foto;
    }
    $kbm->save();
    return redirect('/kbm')->with('success', 'Data KBM berhasil ditambahkan');
}
public function form_selesai_kbm($id) {
    $kbm = Kbm::findOrFail($id);
    $jam_sekarang = Carbon::now()->format('H:i');
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
        DB::table('absensi_siswa')->where('id_kbm', $id)->delete();
        $kbm = Kbm::find($id);
        $kbm->delete();
        return redirect('/kbm')->with('success', 'Data KBM berhasil dihapus'); 
    }
}