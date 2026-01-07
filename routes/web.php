<?php

use App\Http\Controllers\KategoriPengeluaranController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\StatistikController;
use Illuminate\Support\Facades\Route;



Route::get('/', fn() => redirect()->route('pengeluaran.index'));

Route::resource('kategori', KategoriPengeluaranController::class);
Route::resource('pengeluaran', PengeluaranController::class);
Route::get('/statistik', [StatistikController::class, 'index'])->name('statistik.index');
Route::get('/statistik/export', [StatistikController::class, 'export'])->name('statistik.export');