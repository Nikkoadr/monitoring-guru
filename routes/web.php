<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/data_guru', [GuruController::class, 'index']);
Route::get('/data_mapel', [MapelController::class, 'index']);
Route::get('/laporan', [HomeController::class, 'laporan'])->name('laporan');
