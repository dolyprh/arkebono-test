<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\KaryawanController;

Route::get('/', function () {
    return redirect()->route('transaksi.index');
});

Route::resource('transaksi', TransaksiController::class);
Route::get('transaksi/item/{kode}/price', [TransaksiController::class, 'getItemPrice'])->name('transaksi.item.price');

// Master Data Routes
Route::resource('item', ItemController::class)->except(['create', 'edit', 'show']);
Route::resource('karyawan', KaryawanController::class)->except(['create', 'edit', 'show']);
