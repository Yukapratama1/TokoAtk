<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/keranjang', [HomeController::class, 'keranjang'])->name('keranjang');
Route::get('/checkout', [HomeController::class, 'checkout'])->name('checkout');