<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel; 
use App\Imports\DataImport;
use Illuminate\Support\Facades\Gate;

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
            return view('setting');
            }else{
            return redirect('/home')->with('error', 'Anda Tidak Memiliki Akses');
            }
    }

    public function import_data(){
        Excel::import(new DataImport, request()->file('file'));
        return redirect('/setting')->with('success', 'Data User Berhasil Import');
    }
}
