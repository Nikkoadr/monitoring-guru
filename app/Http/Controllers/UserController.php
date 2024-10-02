<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {

        $data_user = DB::table('users')
        ->join('role', 'users.id_role', '=', 'role.id')
        ->select('users.*', 'role.nama_role as nama_role')
        ->get(); 
        return view('user.data_user', compact('data_user'));
    }
}
