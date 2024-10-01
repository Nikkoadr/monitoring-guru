<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\SiswaController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/data_guru', [GuruController::class, 'index']);
Route::get('/form_tambah_guru', [GuruController::class, 'form_tambah_guru']);
Route::get('/data_mapel', [MapelController::class, 'index']);
Route::get('/data_role', [RoleController::class, 'index']);
Route::get('/data_jurusan', [JurusanController::class, 'index']);
Route::get('/data_kelas', [KelasController::class, 'index']);
Route::get('/data_siswa', [SiswaController::class, 'index']);
Route::get('/laporan', [HomeController::class, 'laporan'])->name('laporan');
