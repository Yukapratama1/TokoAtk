<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/keranjang', function () {
    return view('keranjang');
})->name('keranjang');

Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');