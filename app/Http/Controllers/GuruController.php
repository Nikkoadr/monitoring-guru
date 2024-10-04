<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class GuruController extends Controller
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
    public function index(){
        $data_guru = DB::table('guru')
            ->join('users', 'guru.id_user', '=', 'users.id')
            ->select('guru.*', 'users.name as nama_guru')
            ->get();
        return view('guru.data_guru', compact('data_guru'));
    }
    public function form_tambah_guru(){
        return view('guru.form_tambah_guru');
    }

    public function get_user(Request $request)
        {
            $search = $request->query('q');
            $users = User::where('name', 'like', '%' . $search . '%')->get();
            return response()->json($users);
        }


    public function post_guru(Request $request)
        {
            $request->validate([
                'id_guru' => 'required',
            ]);
            $data = [
                'id_user' => $request->id_guru,
                'created_at' => now(),
            ];
            DB::table('guru')->insert($data);
            return redirect('/data_guru')->with('success', 'Data Guru Berhasil Tambah');
        }

    public function hapus_guru($id)
        {
            DB::table('guru')->where('id', $id)->delete();
            return redirect('/data_guru')->with('success', 'Data Guru Berhasil Hapus');
        }
}
