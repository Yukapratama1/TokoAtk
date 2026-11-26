<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function keranjang()
    {
        return view('keranjang');
    }

    public function checkout()
    {
        return view('checkout');
    }
}