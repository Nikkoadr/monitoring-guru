<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    public function index()
    {
        $data_kelas = DB::table('kelas')->get();
        return view('data_kelas', compact('data_kelas'));
    }
}
