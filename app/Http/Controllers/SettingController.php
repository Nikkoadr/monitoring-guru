<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel; 
use App\Imports\DataImport;

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
        return view('setting');
    }

    public function import_data(){
        Excel::import(new DataImport, request()->file('file'));
        return redirect('/setting')->with('success', 'Data User Berhasil Import');
    }
}
