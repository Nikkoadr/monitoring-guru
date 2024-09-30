<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/data_guru', [App\Http\Controllers\HomeController::class, 'data_guru'])->name('data_guru');
Route::get('/data_mapel', [App\Http\Controllers\HomeController::class, 'data_mapel'])->name('data_mapel');
Route::get('/laporan', [App\Http\Controllers\HomeController::class, 'laporan'])->name('laporan');
