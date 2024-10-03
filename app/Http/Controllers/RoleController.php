<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
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
        $data_role = DB::table('role')->get();
        return view('role.data_role', compact('data_role'));
    }

    public function form_tambah_role()
    {
        return view('role.form_tambah_role');
    }

    public function post_role(Request $request)
    {
        $request->validate([
            'nama_role' => 'required',
        ]);
        $data = [
            'nama_role' => $request->nama_role,
            'created_at' => now(),
        ];
        DB::table('role')->insert($data);
        return redirect('/data_role')->with('success', 'Data role Berhasil Tambah');
    }

    public function form_edit_role($id)
    {
        $data_role = DB::table('role')->where('id', $id)->first();
        return view('role.form_edit_role', compact('data_role'));
    }

    public function update_edit_role(Request $request,$id){
        $request->validate([
            'nama_role' => 'required',
        ]);
        $data = [
            'nama_role' => $request->nama_role,
            'updated_at' => now(),
        ];
        DB::table('role')->where('id', $id)->update($data);
        return redirect('/data_role')->with('success', 'Data role Berhasil Update');
    }

    public function hapus_role($id)
    {
        DB::table('role')->where('id', $id)->delete();
        return redirect('/data_role')->with('success', 'Data role Berhasil Hapus');
    }
}
