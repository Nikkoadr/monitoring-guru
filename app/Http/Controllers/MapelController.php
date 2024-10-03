<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Guru;

class MapelController extends Controller
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
        $data_mapel = DB::table('mapel')
            ->join('guru', 'mapel.id_guru', '=', 'guru.id')
            ->join('users', 'guru.id_user', '=', 'users.id')
            ->select('mapel.*', 'guru.id as id_guru', 'users.name as nama_guru')
            ->get();
            
        return view('mapel.data_mapel', compact('data_mapel'));
    }

    public function form_tambah_mapel()
    {
        $guru = DB::table('guru')->get();
        return view('mapel.form_tambah_mapel', compact('guru'));
    }

    public function get_guru(Request $request)
        {
            $search = $request->input('q');
            $gurus = DB::table('guru')
                ->join('users', 'guru.id_user', '=', 'users.id')
                ->where('users.name', 'LIKE', "%{$search}%")
                ->select('guru.id', 'users.name')
                ->get();
            
            return response()->json($gurus);
        }

        public function post_mapel(Request $request)
        {
            $request->validate([
                'nama_mapel' => 'required',
                'id_guru' => 'required',
            ]);
            $data = [
                'nama_mapel' => $request->nama_mapel,
                'id_guru' => $request->id_guru,
                'created_at' => now(),
            ];
            DB::table('mapel')->insert($data);
            return redirect('/data_mapel')->with('success', 'Data Mapel Berhasil Tambah');
        }

        public function form_edit_mapel($id)
        {
            $mapel = DB::table('mapel')->where('id', $id)->first();
            $guru = DB::table('guru')->get();
            return view('mapel.form_edit_mapel', compact('mapel', 'guru'));
        }

        public function update_mapel(Request $request, $id)
        {
            $request->validate([
                'nama_mapel' => 'required',
            ]);
            $data = [
                'nama_mapel' => $request->nama_mapel,
                'updated_at' => now(),
            ];
            DB::table('mapel')->where('id', $id)->update($data);
            return redirect('/data_mapel')->with('success', 'Data Mapel Bereah Edit');
        }

        public  function hapus_mapel($id)
        {
            DB::table('mapel')->where('id', $id)->delete();
            return redirect('/data_mapel')->with('success', 'Data Mapel Berhasil Hapus');
        }
}
