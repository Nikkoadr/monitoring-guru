<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
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
            $data_user = DB::table('users')
            ->join('role', 'users.id_role', '=', 'role.id')
            ->select('users.*', 'role.nama_role as nama_role')
            ->get(); 
            return view('user.data_user', compact('data_user'));
        }else{
            return redirect('/home')->with('error', 'Anda Tidak Memiliki Akses');
        }
    }

    public function form_tambah_user()
    {
        return view('user.form_tambah_user');
    }

    public function post_user(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);
        DB::table('users')->insert([
            'id_role' => 3,//Siswa
            'gelar_depan' => $request->gelar_depan,
            'name' => $request->name,
            'gelar_belakang' => $request->gelar_belakang,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        return redirect('/data_user')->with('success', 'Data User Berhasil Tambah');
    }

    public function form_edit_user($id)
    {
        $roles = DB::table('role')->get();
        $data_user = DB::table('users')->where('id', $id)->first();
        return view('user.form_edit_user', compact('data_user', 'roles'));
    }

    public function update_user(Request $request, $id)
    {
        $request->validate([
            'id_role' => 'required',
            'name' => 'required',
            'email' => 'required',
        ]);

        DB::table('users')->where('id', $id)->update([
            'id_role' => $request->id_role,
            'gelar_depan' => $request->gelar_depan,
            'name' => $request->name,
            'gelar_belakang' => $request->gelar_belakang,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        return redirect('/data_user')->with('success', 'Data User Berhasil Edit');
    }
    public function form_edit_password_user($id)
        {
            $data_user = DB::table('users')->where('id', $id)->first();
            return view('user.form_edit_password_user', compact('data_user'));
        }

    public function update_password_user(Request $request,$id){
        $request->validate([
            'password' => 'required | confirmed',
        ]);
        DB::table('users')->where('id', $id)->update([
            'password' => bcrypt($request->password),
        ]);
        return redirect('/data_user')->with('success', 'Data User Berhasil Edit');
    }

    public function hapus_user($id)
    {
        DB::table('users')->where('id', $id)->delete();
        return redirect('/data_user')->with('success', 'Data User Berhasil Hapus');
    }

    public function hapus_dataset_wajah($id)
    {
        DB::table('users')->where('id', $id)->update(['dataset_wajah' => null]);
        return redirect('/data_user')->with('success', 'Dataset Wajah Berhasil Dihapus');
    }
}