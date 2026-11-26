@extends('layouts.app')

@section('title', 'Checkout — Toko ATK')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
@endsection

@section('content')
    <div class="checkout-header">
        <div>
            <a href="{{ route('keranjang') }}" class="back-link">&larr; Kembali ke Keranjang</a>
            <h1 class="checkout-page-title">Data Pesanan & Pembayaran</h1>
        </div>
    </div>

    <!-- Tahap 1: Form Checkout -->
    <form id="tahapForm" class="checkout-layout" novalidate>
        @csrf

        <div class="checkout-form-box">
            <div class="form-group">
                <label class="field-label" for="namaPemesan">Nama pemesan</label>
                <input type="text" id="namaPemesan" name="nama_pemesan" class="input-form" placeholder="Nama lengkap" autocomplete="name">
                <div class="field-error" id="errNama">Isi nama pemesan.</div>
            </div>

            <div class="form-group">
                <label class="field-label" for="noWa">Nomor WhatsApp</label>
                <input type="tel" id="noWa" name="no_wa" class="input-form" placeholder="08123456789" inputmode="numeric" autocomplete="tel">
                <div class="field-error" id="errWa">Isi nomor WhatsApp yang valid (9–14 angka).</div>
            </div>

            <div class="form-group">
                <span class="field-label">Pengambilan</span>
                <div class="opsi-row">
                    <label class="opsi">
                        <input type="radio" name="pengambilan" value="ambil" checked>
                        <span>
                            <span class="opsi-nama">Ambil di toko</span>
                            <span class="opsi-ket">Gratis</span>
                        </span>
                    </label>
                    <label class="opsi">
                        <input type="radio" name="pengambilan" value="kirim">
                        <span>
                            <span class="opsi-nama">Kirim ke alamat</span>
                            <span class="opsi-ket" id="ketOngkir">+ Rp 8.000</span>
                        </span>
                    </label>
                </div>
            </div>

            <div class="form-group" id="grupAlamat" style="display:none;">
                <label class="field-label" for="alamat">Alamat pengiriman</label>
                <textarea id="alamat" name="alamat" class="input-form" rows="3" placeholder="Jalan, nomor, RT/RW, kelurahan, patokan"></textarea>
                <div class="field-error" id="errAlamat">Isi alamat pengiriman.</div>
            </div>

            <div class="form-group">
                <label class="field-label" for="catatanPesanan">Catatan (opsional)</label>
                <input type="text" id="catatanPesanan" name="catatan" class="input-form" placeholder="Misal: warna sticky notes campur">
            </div>

            <div class="form-group">
                <span class="field-label">Metode pembayaran</span>
                <div class="opsi-list" id="listBayar"></div>
                <div class="field-error" id="errBayar">Pilih metode pembayaran.</div>
            </div>
        </div>

        <aside class="checkout-summary">
            <h3>Ringkasan Pesanan</h3>

            <div class="ringkasan">
                <div class="ringkasan-row"><span>Subtotal</span><span id="rSubtotal">Rp 0</span></div>
                <div class="ringkasan-row discount"><span>Promo Min. Rp 50.000</span><span id="rDiskon">- Rp 0</span></div>
                <div class="ringkasan-row"><span>Ongkos kirim</span><span id="rOngkir">Gratis</span></div>
                <div class="ringkasan-row total"><span>Total bayar</span><span id="rTotal">Rp 0</span></div>
            </div>

            <button type="submit" class="btn-primary btn-block">Buat Pesanan</button>
        </aside>
    </form>

    <!-- Tahap 2: Hasil Pesanan & Konfirmasi WA -->
    <div id="tahapHasil" class="hasil-card" style="display:none;">
        <div id="isiHasil"></div>
        <div class="hasil-actions">
            <a class="btn-wa" id="linkWa" href="#" target="_blank" rel="noopener">Kirim konfirmasi via WhatsApp</a>
            <a class="btn-ghost" href="{{ route('home') }}">Kembali belanja</a>
        </div>
    </div>
@endsection