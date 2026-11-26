{{-- Bagian keranjang + checkout + pembayaran (pemilik: bagian checkout) --}}
{{-- Pakai dengan: @include('partials.checkout') di welcome.blade.php, SEBELUM <script> halaman katalog --}}
<link rel="stylesheet" href="{{ asset('css/checkout.css') }}">

<!-- Overlay & Laci Keranjang -->
    <div class="overlay" id="overlay" onclick="tutupKeranjang()"></div>
    <aside class="drawer" id="drawer">
        <div class="drawer-header">
            <h3>Daftar Belanja</h3>
            <button class="btn-close" onclick="tutupKeranjang()" aria-label="Tutup keranjang">&times;</button>
        </div>

        <div class="drawer-body" id="isiKeranjang">
            <p class="empty-msg">Keranjang masih kosong.</p>
        </div>

        <div class="drawer-footer">
            <div class="total-row">
                <span>Subtotal</span>
                <strong id="totalHarga">Rp 0</strong>
            </div>
            <button class="btn-primary btn-block" onclick="bukaCheckout()">Lanjut ke Pembayaran</button>
        </div>
    </aside>

    <!-- Modal Checkout -->
    <div class="modal-wrap" id="modalCheckout" role="dialog" aria-modal="true" aria-labelledby="judulCheckout">
        <div class="modal">

            <!-- Tahap 1: Form -->
            <div id="tahapForm" style="display:flex; flex-direction:column; min-height:0;">
                <div class="modal-header">
                    <h3 id="judulCheckout">Data Pesanan & Pembayaran</h3>
                    <button class="btn-close" onclick="tutupCheckout()" aria-label="Tutup">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="field-label" for="namaPemesan">Nama pemesan</label>
                        <input type="text" id="namaPemesan" class="input-form" placeholder="Nama lengkap" autocomplete="name">
                        <div class="field-error" id="errNama">Isi nama pemesan.</div>
                    </div>

                    <div class="form-group">
                        <label class="field-label" for="noWa">Nomor WhatsApp</label>
                        <input type="tel" id="noWa" class="input-form" placeholder="08123456789" inputmode="numeric" autocomplete="tel">
                        <div class="field-error" id="errWa">Isi nomor WhatsApp yang valid (9–14 angka).</div>
                    </div>

                    <div class="form-group">
                        <span class="field-label">Pengambilan</span>
                        <div class="opsi-row">
                            <label class="opsi">
                                <input type="radio" name="pengambilan" value="ambil" checked onchange="hitungRingkasan()">
                                <span><span class="opsi-nama">Ambil di toko</span><span class="opsi-ket">Gratis</span></span>
                            </label>
                            <label class="opsi">
                                <input type="radio" name="pengambilan" value="kirim" onchange="hitungRingkasan()">
                                <span><span class="opsi-nama">Kirim ke alamat</span><span class="opsi-ket" id="ketOngkir"></span></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group" id="grupAlamat" style="display:none;">
                        <label class="field-label" for="alamat">Alamat pengiriman</label>
                        <textarea id="alamat" class="input-form" placeholder="Jalan, nomor, RT/RW, kelurahan, patokan"></textarea>
                        <div class="field-error" id="errAlamat">Isi alamat pengiriman.</div>
                    </div>

                    <div class="form-group">
                        <label class="field-label" for="catatanPesanan">Catatan (opsional)</label>
                        <input type="text" id="catatanPesanan" class="input-form" placeholder="Misal: warna sticky notes campur">
                    </div>

                    <div class="form-group">
                        <span class="field-label">Metode pembayaran</span>
                        <div class="opsi-list" id="listBayar"></div>
                        <div class="field-error" id="errBayar">Pilih metode pembayaran.</div>
                    </div>

                    <div class="ringkasan">
                        <div class="ringkasan-row"><span>Subtotal</span><span id="rSubtotal">Rp 0</span></div>
                        <div class="ringkasan-row"><span>Ongkos kirim</span><span id="rOngkir">Rp 0</span></div>
                        <div class="ringkasan-row total"><span>Total bayar</span><span id="rTotal">Rp 0</span></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn-primary btn-block" onclick="prosesPesanan()">Buat Pesanan</button>
                </div>
            </div>

            <!-- Tahap 2: Hasil -->
            <div id="tahapHasil" style="display:none; flex-direction:column; min-height:0;">
                <div class="modal-body" id="isiHasil"></div>
                <div class="modal-footer">
                    <a class="btn-wa" id="linkWa" href="#" target="_blank" rel="noopener">Kirim konfirmasi via WhatsApp</a>
                    <button class="btn-ghost" onclick="tutupCheckout()">Kembali belanja</button>
                </div>
            </div>

        </div>
    </div>

    <!-- Toast Notifikasi -->
    <div class="toast" id="toast">Pesanan dicatat!</div>

<script src="{{ asset('js/checkout.js') }}"></script>