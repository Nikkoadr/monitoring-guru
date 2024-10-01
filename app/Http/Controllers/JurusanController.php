<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JurusanController extends Controller
{
    public function index()
    {
        $data_jurusan = DB::table('jurusan')->get();
        return view('data_jurusan', compact('data_jurusan'));
    }
}
