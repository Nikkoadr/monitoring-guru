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
        $data_guru = DB::table('guru_mapel')
            ->join('users', 'guru_mapel.id_user', '=', 'users.id')
            ->select('guru_mapel.*', 'users.name as nama_guru')
            ->get();
        return view('guru.data_guru', compact('data_guru'));
    }
    public function form_tambah_guru(Request $request){
        return view('guru.form_tambah_guru');
    }

    public function get_guru(Request $request)
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
            DB::table('guru_mapel')->insert($data);
            return response()->json(['redirect' => url('/data_guru')]);
        }

    public function hapus_guru($id)
        {
            DB::table('guru_mapel')->where('id', $id)->delete();
            return redirect('/data_guru');
        }
}
