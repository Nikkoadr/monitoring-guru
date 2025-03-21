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
use App\Http\Controllers\WalasController;
use App\Http\Controllers\Ketua_kelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KbmController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Izin_pendidikController;
use App\Http\Controllers\Izin_siswaController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KepsekController;
use App\Http\Controllers\WakaController;
use App\Http\Controllers\KesiswaanController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

Route::post('/simpan_dataset_wajah', [AbsensiController::class, 'simpanDatasetWajah']);
Route::get('/ambil_dataset_wajah', [AbsensiController::class, 'ambilDatasetWajah']);

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/get_user', [GuruController::class, 'get_user']); //search users menggunakan ajax
Route::get('/get_guru', [MapelController::class, 'get_guru']);//search guru menggunakan ajax
Route::post('/update_status_absensi', [AbsensiController::class, 'updateStatus']);
Route::get('/get_kelas_by_jurusan_{id_jurusan}', [SiswaController::class, 'get_kelas_by_jurusan']);

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
Route::get('/lihat_presensi_siswa_{id}', [AbsensiController::class, 'lihat_presensi_siswa']);

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
Route::get('/form_edit_password_user_{id}', [UserController::class, 'form_edit_password_user']);
Route::put('/update_password_user_{id}', [UserController::class, 'update_password_user']);
Route::put('/update_edit_user_{id}', [UserController::class, 'update_user']);
Route::get('/hapus_user_{id}', [UserController::class, 'hapus_user']);
Route::get('/hapus_dataset_wajah_{id}', [UserController::class, 'hapus_dataset_wajah']);

Route::get('/data_guru', [GuruController::class, 'index']);
Route::get('/form_tambah_guru', [GuruController::class, 'form_tambah_guru']);
Route::post('/post_guru', [GuruController::class, 'post_guru']);
Route::get('/form_edit_guru_{id}', [GuruController::class, 'post_guru']);
Route::post('/update_edit_guru_{id}', [GuruController::class, 'post_guru']);
Route::get('/hapus_guru_{id}', [GuruController::class, 'hapus_guru']);

Route::get('/data_karyawan', [KaryawanController::class, 'index']);
Route::get('/form_tambah_karyawan', [KaryawanController::class, 'form_tambah_karyawan']);
Route::post('/post_karyawan', [KaryawanController::class, 'post_karyawan']);
Route::get('/form_edit_karyawan_{id}', [KaryawanController::class, 'form_edit_karyawan']);
Route::put('/update_karyawan_{id}', [KaryawanController::class, 'update_karyawan']);
Route::get('/hapus_karyawan_{id}', [KaryawanController::class, 'hapus_karyawan']);

Route::get('/data_kepsek', [KepsekController::class, 'index']);
Route::get('/form_tambah_kepsek', [KepsekController::class, 'form_tambah_kepsek']);
Route::post('/post_kepsek', [KepsekController::class, 'post_kepsek']);
Route::get('/form_edit_kepsek_{id}', [KepsekController::class, 'form_edit_kepsek']);
Route::put('/update_edit_kepsek_{id}', [KepsekController::class, 'update_edit_kepsek']);
Route::get('/hapus_kepsek_{id}', [KepsekController::class, 'hapus_kepsek']);

Route::get('/data_waka', [WakaController::class, 'index']);
Route::get('/form_tambah_waka', [WakaController::class, 'form_tambah_waka']);
Route::post('/post_waka', [WakaController::class, 'post_waka']);
Route::get('/form_edit_waka_{id}', [WakaController::class, 'form_edit_waka']);
Route::put('/update_waka_{id}', [WakaController::class, 'update_waka']);
Route::get('/hapus_waka_{id}', [WakaController::class, 'hapus_waka']);

Route::get('/data_kesiswaan', [KesiswaanController::class, 'index']);
Route::get('/form_tambah_kesiswaan', [KesiswaanController::class, 'form_tambah_kesiswaan']);
Route::post('/post_kesiswaan', [KesiswaanController::class, 'post_kesiswaan']);
Route::get('/form_edit_kesiswaan_{id}', [KesiswaanController::class, 'form_edit_kesiswaan']);
Route::put('/update_kesiswaan_{id}', [KesiswaanController::class, 'update_kesiswaan']);
Route::get('/hapus_kesiswaan_{id}', [KesiswaanController::class, 'hapus_kesiswaan']);

Route::get('/data_mapel', [MapelController::class, 'index']);
Route::get('/form_tambah_mapel', [MapelController::class, 'form_tambah_mapel']);
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

Route::get('/data_walas', [WalasController::class, 'index']);
Route::get('/form_tambah_walas', [WalasController::class, 'form_tambah_walas']);
Route::get('/form_edit_walas_{id}', [WalasController::class, 'form_edit_walas']);
Route::put('/update_walas_{id}', [WalasController::class, 'update_walas']);
Route::post('/post_walas', [WalasController::class, 'post_walas']);
Route::get('/hapus_walas_{id}', [WalasController::class, 'hapus_walas']);

Route::get('/data_ketua_kelas', [Ketua_kelasController::class, 'index']);
Route::get('/form_tambah_ketua_kelas', [Ketua_kelasController::class, 'form_tambah_ketua_kelas']);
Route::get('/get_ketua_kelas_by_kelas', [Ketua_kelasController::class, 'get_ketua_kelas_by_kelas']);
Route::get('/form_edit_ketua_kelas_{id}', [Ketua_kelasController::class, 'form_edit_ketua_kelas']);
Route::put('/update_ketua_kelas_{id}', [Ketua_kelasController::class, 'update_ketua_kelas']);
Route::post('/post_ketua_kelas', [Ketua_kelasController::class, 'post_ketua_kelas']);
Route::get('/hapus_ketua_kelas_{id}', [Ketua_kelasController::class, 'hapus_ketua_kelas']);

Route::get('/data_siswa', [SiswaController::class, 'index']);
Route::get('/form_tambah_siswa', [SiswaController::class, 'form_tambah_siswa']);
Route::post('/post_siswa', [SiswaController::class, 'post_siswa']);
Route::get('/form_edit_siswa_{id}', [SiswaController::class, 'form_edit_siswa']);
Route::put('/update_siswa_{id}', [SiswaController::class, 'update_siswa']);
Route::get('/hapus_siswa_{id}', [SiswaController::class, 'hapus_siswa']);

Route::get('/setting', [SettingController::class, 'index']);
Route::put('/edit_setting/{id}', [SettingController::class, 'edit_setting']);
Route::post('/import_data', [SettingController::class, 'import_data']);

Route::get('/data_kesiswaan', [KesiswaanController::class, 'index']);
Route::get('/form_tambah_kesiswaan', [KesiswaanController::class, 'form_tambah_kesiswaan']);

Route::get('/data_izin_siswa', [Izin_siswaController::class, 'data_izin_siswa']);
Route::get('/req_izin_siswa', [Izin_siswaController::class, 'req_izin_siswa']);
Route::get('/edit_izin_siswa_{id}', [Izin_siswaController::class, 'edit_izin_siswa']);
Route::patch('/update_izin_siswa_{id}', [Izin_siswaController::class, 'update_izin_siswa']);
Route::post('/post_izin_siswa', [Izin_siswaController::class, 'post_izin_siswa']);
Route::get('/print_izin_siswa_{id}', [Izin_siswaController::class, 'print_surat_izin_siswa']);

Route::get('/data_izin_pendidik', [Izin_pendidikController::class, 'index']);
Route::get('/izin_pendidik/acc/{id}', [Izin_pendidikController::class, 'acc']);
Route::get('/izin_pendidik/reject/{id}', [Izin_pendidikController::class, 'reject']);
Route::get('/request_izin_pendidik', [Izin_pendidikController::class, 'request_izin_pendidik']);
Route::put('/post_request_izin_pendidik', [Izin_pendidikController::class, 'post_request_izin_pendidik']);


Route::get('/laporan', [LaporanController::class, 'index']);
Route::put('/print_laporan_bulanan_pendidik', [LaporanController::class, 'print_laporan_bulanan_pendidik']);
Route::put('/print_laporan_bulanan_kelas', [LaporanController::class, 'print_laporan_bulanan_kelas']);

Route::get('/presensi_pendidik', [AbsensiController::class, 'presensi_pendidik']);
Route::post('/post_presensi_pendidik', [AbsensiController::class, 'post_presensi_pendidik']);

Route::get('/data_presensi_pendidik', [AbsensiController::class, 'data_presensi_pendidik']);
Route::get('/form_tambah_presensi_pendidik', [AbsensiController::class, 'form_tambah_presensi_pendidik']);
Route::post('/admin_post_presensi_pendidik', [AbsensiController::class, 'admin_post_presensi_pendidik']);
Route::get('/form_edit_presensi_pendidik_{id}', [AbsensiController::class, 'form_edit_presensi_pendidik']);
Route::put('/update_presensi_pendidik_{id}', [AbsensiController::class, 'update_presensi_pendidik']);
Route::get('/hapus_presensi_pendidik_{id}', [AbsensiController::class, 'hapus_presensi_pendidik']);
