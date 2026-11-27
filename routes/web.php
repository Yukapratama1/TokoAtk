<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AtkStockController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/keranjang', [HomeController::class, 'keranjang'])->name('keranjang');
Route::get('/checkout', [HomeController::class, 'checkout'])->name('checkout');

Route::get('/atk', [AtkStockController::class, 'index'])->name('atk.index');
Route::post('/atk', [AtkStockController::class, 'store'])->name('atk.store');
Route::put('/atk/{id}', [AtkStockController::class, 'update'])->name('atk.update');
Route::delete('/atk/{id}', [AtkStockController::class, 'destroy'])->name('atk.destroy');