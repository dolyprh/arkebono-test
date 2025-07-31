<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransaksiController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('transaksi', TransaksiController::class);
Route::get('transaksi/item/{kode}/price', [TransaksiController::class, 'getItemPrice'])->name('transaksi.item.price');
