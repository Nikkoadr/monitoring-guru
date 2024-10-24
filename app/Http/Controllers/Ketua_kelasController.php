<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class Ketua_kelasController extends Controller{
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
    public function index(){
        $user = Auth::user();
        $isKmKelas = DB::table('ketua_kelas')
                ->join('siswa', 'ketua_kelas.id_siswa', '=', 'siswa.id')
                ->where('siswa.id_user', $user->id)
                ->exists();

        $isWalas = DB::table('walas')
                    ->join('guru', 'walas.id_guru', '=', 'guru.id')
                    ->where('guru.id_user', $user->id)
                    ->exists();
        if (Gate::allows('admin')) {
            $data_ketua_kelas = DB::table('ketua_kelas')
                ->join('siswa', 'ketua_kelas.id_siswa', '=', 'siswa.id')
                ->join('users', 'siswa.id_user', '=', 'users.id')
                ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id')
                ->select('ketua_kelas.*', 'users.name as nama_ketua_kelas', 'kelas.nama_kelas')
                ->get();
            return view('ketua_kelas.data_ketua_kelas', compact('data_ketua_kelas'));

        } elseif ($isWalas) {
            $session = Auth::user()->id;
            $kelas_yang_diampu = DB::table('walas')
                ->join('guru', 'walas.id_guru', '=', 'guru.id')
                ->join('users', 'guru.id_user', '=', 'users.id')
                ->join('kelas', 'walas.id_kelas', '=', 'kelas.id')
                ->where('users.id', $session)
                ->select('kelas.id', 'kelas.nama_kelas')
                ->first();

            if ($kelas_yang_diampu) {
                $data_ketua_kelas = DB::table('ketua_kelas')
                    ->join('siswa', 'ketua_kelas.id_siswa', '=', 'siswa.id')
                    ->join('users', 'siswa.id_user', '=', 'users.id')
                    ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id')
                    ->where('kelas.id', $kelas_yang_diampu->id)
                    ->select('ketua_kelas.*', 'users.name as nama_ketua_kelas', 'kelas.nama_kelas')
                    ->get();

                return view('ketua_kelas.data_ketua_kelas', compact('data_ketua_kelas'));
            } else {
                return redirect('/home')->with('error', 'Anda tidak memiliki kelas yang diampu.');
            }

        } else {
            return redirect('/home')->with('error', 'Anda Tidak Memiliki Akses');
        }
    }

    public function form_tambah_ketua_kelas(){
        $id_user = Auth::user()->id;

        if (Auth::user()->id_role == '1') {
            $data_kelas = DB::table('kelas')->get();
        } else {
            $guru = DB::table('guru')->where('id_user', $id_user)->first();
            $id_guru = $guru->id;
            $data_kelas = DB::table('walas')
                            ->join('kelas', 'walas.id_kelas', '=', 'kelas.id')
                            ->where('walas.id_guru', $id_guru)
                            ->select('kelas.*')
                            ->get();
        }
        return view('ketua_kelas.form_tambah_ketua_kelas', compact('data_kelas'));
    }

    public function get_ketua_kelas_by_kelas(Request $request) {
        $id_kelas = $request->id_kelas;
        $query = $request->q;

        $siswa = Siswa::where('id_kelas', $id_kelas)
                    ->whereHas('user', function ($queryBuilder) use ($query) {
                        $queryBuilder->where('name', 'LIKE', "%{$query}%");
                    })
                    ->with('user:id,name')
                    ->get();

        if ($siswa->isEmpty()) {
            return response()->json(['message' => 'Siswa tidak ada di kelas Anda.'], 404);
        }

        return response()->json($siswa->map(function ($s) {
            return [
                'id' => $s->id,
                'name' => $s->user->name,
            ];
        }));
    }

    public function post_ketua_kelas(Request $request){
        $data = [
            'id_kelas' => $request->id_kelas,
            'id_siswa' => $request->id_siswa,
            'keterangan' => $request->keterangan,
        ];
        DB::table('ketua_kelas')->insert($data);
        return redirect('/data_ketua_kelas')->with('success', 'Data Berhasil Tambah');
    }
}
