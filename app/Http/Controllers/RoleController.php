<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $data_role = DB::table('role')->get();
        return view('role.data_role', compact('data_role'));
    }
}
