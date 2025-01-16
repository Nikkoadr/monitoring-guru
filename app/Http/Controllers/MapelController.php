<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Mapel;
use Illuminate\Support\Facades\Gate;

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
        if (Gate::allows('admin')) {
        $data_mapel = Mapel::with('guru.user')->get();
        return view('mapel.data_mapel', compact('data_mapel'));
        }else{
            return redirect('/home')->with('error', 'Anda Tidak Memiliki Akses');
        }
    }

    public function form_tambah_mapel()
    {
        $guru = DB::table('guru')->get();
        return view('mapel.form_tambah_mapel', compact('guru'));
    }

    public function form_tambah_guru_pengampu($id){
        $mapel = DB::table('mapel')->where('id', $id)->first();
        return view('mapel.form_tambah_guru_pengampu', compact('mapel'));
    }

    public function get_guru(Request $request)
        {
            $search = $request->input('q');
            $guru = DB::table('guru')
                ->join('users', 'guru.id_user', '=', 'users.id')
                ->where('users.name', 'LIKE', "%{$search}%")
                ->select('guru.id', 'users.name')
                ->get();
            
            return response()->json($guru);
        }

        public function post_guru_pengampu(Request $request, $id){
            $request->validate([
                'id_guru' => 'required',
            ]);
            $data = [
                'id_mapel' => $id,
                'id_guru' => $request->id_guru,
                'created_at' => now(),
            ];
            DB::table('guru_mapel')->insert($data);
            return redirect('/data_mapel')->with('success', 'Data Guru Berhasil Tambah');
        }

        public function post_mapel(Request $request)
        {
            $request->validate([
                'nama_mapel' => 'required',
            ]);
            $data = [
                'nama_mapel' => $request->nama_mapel,
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
            DB::table('guru_mapel')->where('id_mapel', $id)->delete();
            DB::table('mapel')->where('id', $id)->delete();
            return redirect('/data_mapel')->with('success', 'Data Mapel Berhasil Hapus');
        }
}
