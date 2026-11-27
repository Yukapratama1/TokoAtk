@extends('layouts.app')

@section('title', 'Toko ATK — Etalase Alat Tulis Harian')

@section('content')
    <!-- Bar Pencarian di Tengah & Tombol Kelola Stok + Keranjang di Pojok Kanan -->
    <div class="home-topbar">
        <div class="home-topbar-spacer"></div>

        <div class="search-box">
            <input type="text" id="inputCari" name="q" placeholder="Cari pulpen, kertas HVS, buku...">
        </div>

        <div style="display: flex; align-items: center; gap: 10px;">
            <a href="{{ route('atk.index') }}" class="btn-keranjang" style="background: #f8fafc; color: #0f172a; border: 1px solid #cbd5e1;">
                Kelola Stok
            </a>
            <a href="{{ route('keranjang') }}" class="btn-keranjang">
                Keranjang <span class="badge-cart">0</span>
            </a>
        </div>
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

    <!-- 2. Deretan Chip Kategori (Dinamis dari Database) -->
    <section class="category-strip">
        <button type="button" class="cat-chip active" data-kat="Semua">
            <span class="cat-chip-name">Semua Barang</span>
            <span class="cat-chip-count">{{ $produks->count() }} item</span>
        </button>

        @foreach ($kategoris as $kat)
            <button type="button" class="cat-chip" data-kat="{{ $kat->nama_kategori }}">
                <span class="cat-chip-name">{{ $kat->nama_kategori }}</span>
                <span class="cat-chip-count">{{ $kat->total_produk }} item</span>
            </button>
        @endforeach
    </section>

    <!-- 3. Header & Grid Katalog Produk (Dinamis dari Database) -->
    <div class="catalog-header">
        <h2 class="catalog-title">Semua Barang</h2>
        <span class="jumlah-produk">Menampilkan {{ $produks->count() }} barang</span>
    </div>

    @php
        $gambarProduk = [
            'ATK-001' => 'https://images.unsplash.com/photo-1585336261022-680e295ce3fe?auto=format&fit=crop&w=500&q=80',
            'ATK-002' => 'https://images.unsplash.com/photo-1586075010923-2dd4570fb338?auto=format&fit=crop&w=500&q=80',
            'ATK-003' => 'https://images.unsplash.com/photo-1531346878377-a5be20888e57?auto=format&fit=crop&w=500&q=80',
            'ATK-004' => 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?auto=format&fit=crop&w=500&q=80',
            'ATK-005' => 'https://images.unsplash.com/photo-1586281380349-632531db7ed4?auto=format&fit=crop&w=500&q=80',
            'ATK-006' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=500&q=80',
        ];
        $defaultGambar = 'https://images.unsplash.com/photo-1456735190827-d1262f71b8a3?auto=format&fit=crop&w=500&q=80';
    @endphp

    <div class="produk-grid">
        @forelse ($produks as $item)
            <div class="card" data-id="{{ $item->id }}" data-kode="{{ $item->kode_produk }}" data-stok="{{ $item->stok }}">
                <img 
                    src="{{ $gambarProduk[$item->kode_produk] ?? $defaultGambar }}" 
                    alt="{{ $item->nama_produk }}" 
                    class="card-img"
                >
                <div class="card-body">
                    <span class="card-cat">{{ $item->nama_kategori }}</span>
                    <h4 class="card-title">{{ $item->nama_produk }}</h4>
                    <p class="card-unit">Kode: {{ $item->kode_produk }} • Stok: {{ $item->stok }}</p>
                    <div class="card-footer">
                        <span class="card-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                        <button class="btn-beli" type="button" {{ $item->stok <= 0 ? 'disabled' : '' }}>
                            {{ $item->stok > 0 ? '+ Beli' : 'Habis' }}
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <p>Belum ada produk tersedia di katalog.</p>
        @endforelse
    </div>
@endsection