@extends('layouts.app')

@section('title', 'Toko ATK — Etalase Alat Tulis Harian')

@section('content')
    <!-- Bar Pencarian di Tengah & Keranjang di Pojok Kanan -->
    <div class="home-topbar">
        <div class="home-topbar-spacer"></div>

        <div class="search-box">
            <input type="text" id="inputCari" name="q" placeholder="Cari pulpen, kertas HVS, buku...">
        </div>

        <a href="{{ route('keranjang') }}" class="btn-keranjang">
            Keranjang <span class="badge-cart">0</span>
        </a>
    </div>

    <!-- 1. Banner & Promo Santai -->
    <section class="promo-banner">
        <div class="promo-content">
            <span class="promo-tag">✨ Promo Minggu Ini</span>
            <h1>Stok alat tulis aman, nugas & kerja makin lancar</h1>
            <p>Potongan <strong>Rp 5.000</strong> otomatis untuk belanja min. Rp 50.000 — atau pilih Paket Nugas hemat siap pakai di etalase.</p>
            <div class="promo-info-box">
                <span class="promo-badge">Paket Nugas • <strong>Rp 28.000</strong></span>
                <span class="promo-note">Isi: 2 Buku Campus + 3 Pulpen Gel + 1 Tipe-X</span>
            </div>
        </div>
        <div class="promo-visual">
            <img src="https://images.unsplash.com/photo-1456735190827-d1262f71b8a3?auto=format&fit=crop&w=600&q=80" alt="Perlengkapan Alat Tulis Harian">
        </div>
    </section>

    <!-- 2. Deretan Chip Kategori -->
    <section class="category-strip">
        <button type="button" class="cat-chip active" data-kat="Semua">
            <span class="cat-chip-name">Semua Barang</span>
            <span class="cat-chip-count">6 item</span>
        </button>
        <button type="button" class="cat-chip" data-kat="Alat Tulis">
            <span class="cat-chip-name">Alat Tulis</span>
            <span class="cat-chip-count">1 item</span>
        </button>
        <button type="button" class="cat-chip" data-kat="Kertas & Buku">
            <span class="cat-chip-name">Kertas & Buku</span>
            <span class="cat-chip-count">3 item</span>
        </button>
        <button type="button" class="cat-chip" data-kat="Kantor & Arsip">
            <span class="cat-chip-name">Kantor & Arsip</span>
            <span class="cat-chip-count">2 item</span>
        </button>
    </section>

    <!-- 3. Header & Grid Katalog Produk -->
    <div class="catalog-header">
        <h2 class="catalog-title">Semua Barang</h2>
        <span class="jumlah-produk">Menampilkan 6 barang</span>
    </div>

    <div class="produk-grid">
        <div class="card">
            <img src="https://images.unsplash.com/photo-1585336261022-680e295ce3fe?auto=format&fit=crop&w=500&q=80" alt="Pulpen Gel Hitam 0.5mm" class="card-img">
            <div class="card-body">
                <span class="card-cat">Alat Tulis</span>
                <h4 class="card-title">Pulpen Gel Hitam 0.5mm</h4>
                <p class="card-unit">1 Pak (12 Pcs)</p>
                <div class="card-footer">
                    <span class="card-price">Rp 24.000</span>
                    <button class="btn-beli" type="button">+ Beli</button>
                </div>
            </div>
        </div>

        <div class="card">
            <img src="https://images.unsplash.com/photo-1586075010923-2dd4570fb338?auto=format&fit=crop&w=500&q=80" alt="Kertas HVS A4 80gsm" class="card-img">
            <div class="card-body">
                <span class="card-cat">Kertas & Buku</span>
                <h4 class="card-title">Kertas HVS A4 80gsm</h4>
                <p class="card-unit">1 Rim (500 Lembar)</p>
                <div class="card-footer">
                    <span class="card-price">Rp 54.000</span>
                    <button class="btn-beli" type="button">+ Beli</button>
                </div>
            </div>
        </div>

        <div class="card">
            <img src="https://images.unsplash.com/photo-1531346878377-a5be20888e57?auto=format&fit=crop&w=500&q=80" alt="Buku Tulis Campus 58 Lbr" class="card-img">
            <div class="card-body">
                <span class="card-cat">Kertas & Buku</span>
                <h4 class="card-title">Buku Tulis Campus 58 Lbr</h4>
                <p class="card-unit">1 Pak (10 Buku)</p>
                <div class="card-footer">
                    <span class="card-price">Rp 42.000</span>
                    <button class="btn-beli" type="button">+ Beli</button>
                </div>
            </div>
        </div>

        <div class="card">
            <img src="https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?auto=format&fit=crop&w=500&q=80" alt="Stapler HD-10 + Isi" class="card-img">
            <div class="card-body">
                <span class="card-cat">Kantor & Arsip</span>
                <h4 class="card-title">Stapler HD-10 + Isi</h4>
                <p class="card-unit">1 Set</p>
                <div class="card-footer">
                    <span class="card-price">Rp 17.500</span>
                    <button class="btn-beli" type="button">+ Beli</button>
                </div>
            </div>
        </div>

        <div class="card">
            <img src="https://images.unsplash.com/photo-1586281380349-632531db7ed4?auto=format&fit=crop&w=500&q=80" alt="Sticky Notes Warna 3x3" class="card-img">
            <div class="card-body">
                <span class="card-cat">Kertas & Buku</span>
                <h4 class="card-title">Sticky Notes Warna 3x3"</h4>
                <p class="card-unit">1 Pad (100 Lembar)</p>
                <div class="card-footer">
                    <span class="card-price">Rp 11.000</span>
                    <button class="btn-beli" type="button">+ Beli</button>
                </div>
            </div>
        </div>

        <div class="card">
            <img src="https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=500&q=80" alt="Map Plastik Kancing Folio" class="card-img">
            <div class="card-body">
                <span class="card-cat">Kantor & Arsip</span>
                <h4 class="card-title">Map Plastik Kancing Folio</h4>
                <p class="card-unit">1 Lusin (12 Pcs)</p>
                <div class="card-footer">
                    <span class="card-price">Rp 29.000</span>
                    <button class="btn-beli" type="button">+ Beli</button>
                </div>
            </div>
        </div>
    </div>
@endsection