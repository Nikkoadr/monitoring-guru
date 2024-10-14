<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class WalasController extends Controller
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
    
    public function index()
    {
        $data_walas = DB::table('walas')
            ->join('guru', 'walas.id_guru', '=', 'guru.id')
            ->join('users', 'guru.id_user', '=', 'users.id')
            ->join('kelas', 'walas.id_kelas', '=', 'kelas.id')
            ->select('walas.*', 'users.name as nama_walas', 'kelas.nama_kelas')
            ->get();

        return view('walas.data_walas', compact('data_walas'));
    }

    public function form_tambah_walas()
    {
        $data_kelas = DB::table('kelas')->get();
        return view('walas.form_tambah_walas', compact('data_kelas'));
    }

    public function get_user(Request $request)
        {
            $search = $request->query('q');
            $users = User::where('name', 'like', '%' . $search . '%')->get();
            return response()->json($users);
        }

    public function post_walas(Request $request){
        $request->validate([
            'id_guru' => 'required',
            'id_kelas' => 'required',
        ]);
        $data = [
            'id_guru' => $request->id_guru,
            'id_kelas' => $request->id_kelas,
            'created_at' => now(),
        ];
        DB::table('walas')->insert($data);
        return redirect('/data_walas')->with('success', 'Data Walas Berhasil Tambah');
    }
}
