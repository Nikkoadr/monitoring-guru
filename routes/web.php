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
use App\Http\Controllers\KbmController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AbsensiController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/kbm', [KbmController::class, 'index']);
Route::get('/form_tambah_kbm', [KbmController::class, 'form_tambah_kbm']);
Route::get('/form_edit_kbm_{id}', [KbmController::class, 'form_edit_kbm']);
Route::put('/update_kbm_{id}', [KbmController::class, 'update_kbm']);
Route::get('/get_guru_by_mapel_{id_mapel}', [KbmController::class, 'get_guru']);
Route::post('/tambah_kbm', [KbmController::class, 'tambah_kbm']);
Route::get('/form_selesai_kbm_{id}', [KbmController::class, 'form_selesai_kbm']);
Route::put('/update_selesai_kbm_{id}', [KbmController::class, 'update_selesai_kbm']);
Route::get('/hapus_kbm_{id}', [KbmController::class, 'hapus_kbm']);
Route::get('/presensi_siswa_{id}', [AbsensiController::class, 'presensi_siswa']);
Route::post('/tambah_presensi', [AbsensiController::class, 'tambah_presensi']);

Route::get('/data_role', [RoleController::class, 'index']);
Route::get('/form_tambah_role', [RoleController::class, 'form_tambah_role']);
Route::post('/post_role', [RoleController::class, 'post_role']);
Route::get('/form_edit_role_{id}', [RoleController::class, 'form_edit_role']);
Route::put('/update_edit_role_{id}', [RoleController::class, 'update_edit_role']);
Route::get('/hapus_role_{id}', [RoleController::class, 'hapus_role']);

Route::get('/data_user', [UserController::class, 'index']);
Route::get('/form_tambah_user', [UserController::class, 'form_tambah_user']);
Route::post('/post_user', [UserController::class, 'post_user']);
Route::get('/form_edit_user_{id}', [UserController::class, 'form_edit_user']);
Route::put('/update_edit_user_{id}', [UserController::class, 'update_user']);
Route::get('/hapus_user_{id}', [UserController::class, 'hapus_user']);

Route::get('/data_guru', [GuruController::class, 'index']);
Route::get('/form_tambah_guru', [GuruController::class, 'form_tambah_guru']);
Route::get('/get_user', [GuruController::class, 'get_user']); //search users menggunakan ajax
Route::post('/post_guru', [GuruController::class, 'post_guru']);
Route::get('/form_edit_guru_{id}', [GuruController::class, 'post_guru']);
Route::post('/update_edit_guru_{id}', [GuruController::class, 'post_guru']);
Route::get('/hapus_guru_{id}', [GuruController::class, 'hapus_guru']);

Route::get('/data_mapel', [MapelController::class, 'index']);
Route::get('/form_tambah_mapel', [MapelController::class, 'form_tambah_mapel']);
Route::get('/get_guru', [MapelController::class, 'get_guru']);
Route::post('/post_mapel', [MapelController::class, 'post_mapel']);
Route::get('/form_edit_mapel_{id}', [MapelController::class, 'form_edit_mapel']);
Route::get('/form_tambah_guru_pengampu_{id}', [MapelController::class, 'form_tambah_guru_pengampu']);
Route::post('/post_guru_pengampu_{id}', [MapelController::class, 'post_guru_pengampu']);
Route::put('/update_mapel_{id}', [MapelController::class, 'update_mapel']);
Route::get('/hapus_mapel_{id}', [MapelController::class, 'hapus_mapel']);

Route::get('/data_jurusan', [JurusanController::class, 'index']);
Route::get('/form_tambah_jurusan', [JurusanController::class, 'form_tambah_jurusan']);
Route::post('/post_jurusan', [JurusanController::class, 'post_jurusan']);
Route::get('/form_edit_jurusan_{id}', [JurusanController::class, 'form_edit_jurusan']);
Route::put('/update_jurusan_{id}', [JurusanController::class, 'update_jurusan']);
Route::get('/hapus_jurusan_{id}', [JurusanController::class, 'hapus_jurusan']);

Route::get('/data_kelas', [KelasController::class, 'index']);
Route::get('/form_tambah_kelas', [KelasController::class, 'form_tambah_kelas']);
Route::post('/post_kelas', [KelasController::class, 'post_kelas']);
Route::get('/form_edit_kelas_{id}', [KelasController::class, 'form_edit_kelas']);
Route::put('/update_kelas_{id}', [KelasController::class, 'update_kelas']);
Route::get('/hapus_kelas_{id}', [KelasController::class, 'hapus_kelas']);

Route::get('/data_siswa', [SiswaController::class, 'index']);
Route::get('/form_tambah_siswa', [SiswaController::class, 'form_tambah_siswa']);
Route::get('/get_kelas_by_jurusan_{id_jurusan}', [SiswaController::class, 'get_kelas_by_jurusan']);
Route::post('/post_siswa', [SiswaController::class, 'post_siswa']);
Route::get('/form_edit_siswa_{id}', [SiswaController::class, 'form_edit_siswa']);
Route::put('/update_siswa_{id}', [SiswaController::class, 'update_siswa']);
Route::get('/hapus_siswa_{id}', [SiswaController::class, 'hapus_siswa']);

Route::get('/laporan', [HomeController::class, 'laporan'])->name('laporan');
Route::get('/setting', [SettingController::class, 'index']);
Route::post('/import_data', [SettingController::class, 'import_data']);
