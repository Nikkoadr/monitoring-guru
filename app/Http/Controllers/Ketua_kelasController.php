<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\User;

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
    public function index(){
        $data_ketua_kelas = DB::table('ketua_kelas')
            ->join('siswa', 'ketua_kelas.id_siswa', '=', 'siswa.id')
            ->join('users', 'siswa.id_user', '=', 'users.id')
            ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id')
            ->select('ketua_kelas.*', 'users.name as nama_ketua_kelas', 'kelas.nama_kelas')
            ->get();


        return view('ketua_kelas.data_ketua_kelas', compact('data_ketua_kelas'));
    }

    public function form_tambah_ketua_kelas(){
        $data_kelas = DB::table('kelas')->get();
        return view('ketua_kelas.form_tambah_ketua_kelas', compact('data_kelas'));
    }

    public function get_ketua_kelas_by_kelas(Request $request) {
        $id_kelas = $request->id_kelas;
        $query = $request->q;

        // Ambil data siswa dari tabel 'siswa' yang berelasi dengan tabel 'users'
        $siswa = Siswa::where('id_kelas', $id_kelas)
                    ->whereHas('user', function ($queryBuilder) use ($query) {
                        $queryBuilder->where('name', 'LIKE', "%{$query}%");
                    })
                    ->with('user:id,name') // Mengambil relasi user dengan kolom id dan name saja
                    ->get();

        // Jika tidak ada siswa di kelas yang dipilih
        if ($siswa->isEmpty()) {
            return response()->json(['message' => 'Siswa tidak ada di kelas Anda.'], 404);
        }

        // Return data dalam format JSON dengan ID siswa
        return response()->json($siswa->map(function ($s) {
            return [
                'id' => $s->id,  // Mengembalikan id dari tabel 'siswa'
                'name' => $s->user->name,  // Nama dari tabel 'users'
            ];
        }));
    }

    public function post_ketua_kelas(Request $request){
        $data = [
            'id_kelas' => $request->id_kelas,
            'id_siswa' => $request->id_siswa,
        ];
        DB::table('ketua_kelas')->insert($data);
        return redirect('/data_ketua_kelas')->with('success', 'Data Berhasilr');
    }
}
