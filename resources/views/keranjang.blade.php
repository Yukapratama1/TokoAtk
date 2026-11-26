@extends('layouts.app')

@section('title', 'Keranjang Belanja — Toko ATK')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/keranjang.css') }}">
@endsection

@section('content')
    <div class="cart-header">
        <div>
            <a href="{{ route('home') }}" class="back-link">&larr; Lanjut Belanja</a>
            <h1 class="cart-page-title">Keranjang Belanja</h1>
        </div>
        <span class="cart-subtitle">2 produk dipilih</span>
    </div>

    <div class="cart-layout">
        <div class="cart-items-box">
            <div class="cart-item">
                <img src="https://images.unsplash.com/photo-1585336261022-680e295ce3fe?auto=format&fit=crop&w=300&q=80" alt="Pulpen Gel Hitam 0.5mm" class="cart-item-img">
                
                <div class="cart-item-info">
                    <span class="card-cat">Alat Tulis</span>
                    <h4 class="cart-item-title">Pulpen Gel Hitam 0.5mm</h4>
                    <p class="cart-item-unit">1 Pak (12 Pcs) • Rp 24.000</p>
                </div>

                <div class="cart-item-actions">
                    <div class="qty-control">
                        <button type="button" class="qty-btn">-</button>
                        <input type="text" class="qty-input" value="1" readonly>
                        <button type="button" class="qty-btn">+</button>
                    </div>
                    <span class="cart-item-subtotal">Rp 24.000</span>
                    <button type="button" class="btn-hapus">Hapus</button>
                </div>
            </div>

            <div class="cart-item">
                <img src="https://images.unsplash.com/photo-1531346878377-a5be20888e57?auto=format&fit=crop&w=300&q=80" alt="Buku Tulis Campus 58 Lbr" class="cart-item-img">
                
                <div class="cart-item-info">
                    <span class="card-cat">Kertas & Buku</span>
                    <h4 class="cart-item-title">Buku Tulis Campus 58 Lbr</h4>
                    <p class="cart-item-unit">1 Pak (10 Buku) • Rp 42.000</p>
                </div>

                <div class="cart-item-actions">
                    <div class="qty-control">
                        <button type="button" class="qty-btn">-</button>
                        <input type="text" class="qty-input" value="1" readonly>
                        <button type="button" class="qty-btn">+</button>
                    </div>
                    <span class="cart-item-subtotal">Rp 42.000</span>
                    <button type="button" class="btn-hapus">Hapus</button>
                </div>
            </div>
        </div>

        <aside class="cart-summary">
            <h3>Ringkasan Belanja</h3>
            
            <div class="summary-row">
                <span>Total Harga (2 barang)</span>
                <span>Rp 66.000</span>
            </div>

            <div class="summary-row discount">
                <span>Promo Min. Rp 50.000</span>
                <span>- Rp 5.000</span>
            </div>

            <p class="promo-hint">Selamat! Kamu dapat potongan Rp 5.000.</p>

            <hr class="summary-divider">

            <div class="summary-total">
                <span>Total Bayar</span>
                <span>Rp 61.000</span>
            </div>

            <a href="{{ route('checkout') }}" class="btn-checkout">
                Lanjut ke Checkout &rarr;
            </a>
        </aside>
    </div>
@endsection