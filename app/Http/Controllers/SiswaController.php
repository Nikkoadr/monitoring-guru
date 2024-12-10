<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\kelas;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
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
        $isWalas = DB::table('walas')
            ->join('guru', 'walas.id_guru', '=', 'guru.id')
            ->where('guru.id_user', $user->id)
            ->exists();
        if (Gate::allows('admin')) {
        $data_siswa = DB::table('siswa')
            ->join('users', 'siswa.id_user', '=', 'users.id')
            ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id')
            ->join('jurusan', 'siswa.id_jurusan', '=', 'jurusan.id')
            ->select('siswa.*', 'users.name as nama_siswa', 'kelas.nama_kelas', 'jurusan.nama_jurusan')
            ->get();
            return view('siswa.data_siswa', compact('data_siswa'));
            }elseif($isWalas){
                $id_kelas = DB::table('walas')->value('id_kelas');
                $data_siswa = DB::table('siswa')
                    ->join('users', 'siswa.id_user', '=', 'users.id')
                    ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id')
                    ->join('jurusan', 'siswa.id_jurusan', '=', 'jurusan.id')
                    ->join('walas', 'kelas.id', '=', 'walas.id_kelas')
                    ->select('siswa.*', 'users.name as nama_siswa', 'kelas.nama_kelas', 'jurusan.nama_jurusan')
                    ->where('walas.id_kelas', $id_kelas)
                    ->get();

                return view('siswa.data_siswa', compact('data_siswa'));

            }
            else{
            return redirect('/home')->with('error', 'Anda Tidak Memiliki Akses');
            }
    }

public function form_tambah_siswa()
{
    $user = Auth::user();  // Ambil user yang sedang login

    // Periksa apakah user adalah Wali Kelas
    $isWalas = DB::table('walas')
        ->join('guru', 'walas.id_guru', '=', 'guru.id')
        ->where('guru.id_user', $user->id)
        ->exists();

    $data_jurusan = collect();
    $data_kelas = collect();

    if (Gate::allows('admin')) {
        $data_jurusan = DB::table('jurusan')->get();
        $data_kelas = DB::table('kelas')->get();
    return view('siswa.form_tambah_siswa', compact('data_jurusan', 'data_kelas'));
    } 
    elseif ($isWalas) {
        $guru = DB::table('guru')->where('id_user', $user->id)->first();
        $data_jurusan = DB::table('jurusan')
            ->whereIn('jurusan.id', function ($query) use ($guru) {
                $query->select('kelas.id_jurusan')
                    ->from('kelas')
                    ->join('walas', 'kelas.id', '=', 'walas.id_kelas')
                    ->where('walas.id_guru', $guru->id);
            })
            ->first();
            
        $data_kelas = DB::table('kelas')
            ->join('walas', 'kelas.id', '=', 'walas.id_kelas')
            ->where('walas.id_guru', $guru->id)
            ->select('kelas.*')
            ->first();
        return view('siswa.form_tambah_siswa_from_walas', compact('data_jurusan', 'data_kelas'));
    } else {
        return redirect('/home')->with('error', 'Anda Tidak Memiliki Akses');
    }


}

    public function get_user(Request $request)
    {
            $search = $request->query('q');
            $users = User::where('name', 'like', '%' . $search . '%')->get();
            return response()->json($users);
    }

    public function get_kelas_by_jurusan($id_jurusan)
    {
        $kelas = Kelas::where('id_jurusan', $id_jurusan)->get();
        return response()->json($kelas);
    }

    public function post_siswa(Request $request)
    {
        $request->validate([
            'id_jurusan' => 'required',
            'id_kelas' => 'required',
            'id_user' => 'required',
        ]);

        DB::table('siswa')->insert([
            'id_jurusan' => $request->id_jurusan,
            'id_kelas' => $request->id_kelas,
            'id_user' => $request->id_user,
        ]);
        return redirect('/data_siswa')->with('success', 'Data siswa berhasil ditambahkan');

    }

    public function form_edit_siswa($id)
    {
        // Ambil data siswa berdasarkan id
        $data_siswa = DB::table('siswa')
            ->join('users', 'siswa.id_user', '=', 'users.id')
            ->select('siswa.*', 'users.name as nama_siswa')
            ->where('siswa.id', $id)
            ->first();

        // Ambil semua data jurusan
        $data_jurusan = DB::table('jurusan')->get();

        // Ambil data kelas yang berhubungan dengan jurusan yang dipilih oleh siswa
        $data_kelas = DB::table('kelas')
            ->where('id_jurusan', $data_siswa->id_jurusan) // Filter kelas berdasarkan jurusan siswa
            ->get();

        // Kirim data ke view
        return view('siswa.form_edit_siswa', compact('data_siswa', 'data_jurusan', 'data_kelas'));
    }


    public function update_siswa(Request $request, $id)
    {
        $request->validate([
            'id_jurusan' => 'required',
            'id_kelas' => 'required',
            'id_user' => 'required',
        ]);
        $data = [
            'id_jurusan' => $request->id_jurusan,
            'id_kelas' => $request->id_kelas,
            'id_user' => $request->id_user,
            'updated_at' => now(),
        ];
        DB::table('siswa')->where('id', $id)->update($data);
        return redirect('/data_siswa')->with('success', 'Data siswa berhasil Edit');
    }

    public function hapus_siswa($id)
    {
        DB::table('siswa')->where('id', $id)->delete();
        return redirect('/data_siswa')->with('success', 'Data siswa berhasil di hapus');
    }
}
