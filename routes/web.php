<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\SiswaController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/data_role', [RoleController::class, 'index']);
Route::get('/form_tambah_role', [RoleController::class, 'form_tambah_role']);
Route::post('/post_role', [RoleController::class, 'post_role']);
Route::get('/form_edit_role_{id}', [RoleController::class, 'form_edit_role']);
Route::put('/update_edit_role_{id}', [RoleController::class, 'update_edit_role']);
Route::get('/hapus_role_{id}', [RoleController::class, 'hapus_role']);
Route::get('/data_user', [UserController::class, 'index']);
Route::get('/data_guru', [GuruController::class, 'index']);
Route::get('/form_tambah_guru', [GuruController::class, 'form_tambah_guru']);
Route::get('/get_guru', [GuruController::class, 'get_guru']);
Route::post('/post_guru', [GuruController::class, 'post_guru']);
Route::get('/hapus_guru_{id}', [GuruController::class, 'hapus_guru']);
Route::get('/data_mapel', [MapelController::class, 'index']);
Route::get('/data_jurusan', [JurusanController::class, 'index']);
Route::get('/data_kelas', [KelasController::class, 'index']);
Route::get('/data_siswa', [SiswaController::class, 'index']);
Route::get('/laporan', [HomeController::class, 'laporan'])->name('laporan');
