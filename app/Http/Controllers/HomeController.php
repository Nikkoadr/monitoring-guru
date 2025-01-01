<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
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
    // Tanggal hari ini
    $hariIni = Carbon::today();

    // Ambil semua kelas
    $kelas = DB::table('kelas')->get();

    // Ambil ID kelas yang sudah ada di tabel KBM pada hari ini
    $kelasAda = DB::table('kbm')
        ->whereDate('tanggal', $hariIni) // Filter berdasarkan hari ini
        ->pluck('id_kelas') // Hanya ambil ID kelas
        ->toArray();

    // Tambahkan status ke setiap kelas
    $kelas = $kelas->map(function ($item) use ($kelasAda) {
        $item->status = in_array($item->id, $kelasAda) 
            ? 'Sudah ada di KBM' 
            : 'Kelas ini belum ada di KBM';
        return $item;
    });

    return view('home', [
        'kelas' => $kelas,
        'hariIni' => $hariIni->toFormattedDateString(),
    ]);
}
    public function laporan()
    {
        return view('laporan');
    }
}
