<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataImport;

class SettingController extends Controller
{
    public function index()
    {
        return view('setting');
    }

    public function import_data(){
        Excel::import(new DataImport, request()->file('file'));
        return redirect('/setting')->with('success', 'Data User Berhasil Import');
    }
}
