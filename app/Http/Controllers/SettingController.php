<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel; 
use App\Imports\DataImport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
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
            $setting = Setting::first();

            return view('setting', compact('setting'));
            }else{
            return redirect('/home')->with('error', 'Anda Tidak Memiliki Akses');
            }
    }

    public function import_data(){
        Excel::import(new DataImport, request()->file('file'));
        return redirect('/setting')->with('success', 'Data User Berhasil Import');
    }
    public function editSetting(Request $request)
    {
        $data_valid = $request->validate([
            'nama_aplikasi' => ['required'],
            'lokasi_latitude' => ['required'],
            'lokasi_longitude' => ['required'],
            'lokasi_radius' => ['required'],
            'mulai_presensi' => ['required'],
            'limit_presensi' => ['required'],
        ]);
        $setting = Setting::find($request->id);
        $setting->update($data_valid);
        return redirect('setting')->with('success', 'Data Berhasil di Update');
    }
}
