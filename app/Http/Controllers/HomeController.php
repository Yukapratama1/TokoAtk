<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Ambil daftar kategori beserta jumlah produk aktif
        $kategoris = DB::table('kategori')
            ->leftJoin('produk', function ($join) {
                $join->on('kategori.id', '=', 'produk.kategori_id')
                     ->whereNull('produk.deleted_at');
            })
            ->select(
                'kategori.id',
                'kategori.nama_kategori',
                DB::raw('COUNT(produk.id) as total_produk')
            )
            ->groupBy('kategori.id', 'kategori.nama_kategori')
            ->orderBy('kategori.id', 'asc')
            ->get();

        // 2. Ambil daftar produk aktif beserta nama kategorinya
        $produks = DB::table('produk')
            ->join('kategori', 'produk.kategori_id', '=', 'kategori.id')
            ->whereNull('produk.deleted_at')
            ->select('produk.*', 'kategori.nama_kategori')
            ->orderBy('produk.id', 'asc')
            ->get();

        // 3. Kirim ke view home.blade.php
        return view('home', compact('kategoris', 'produks'));
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