(function () {
    'use strict';

    /* ====== PENGATURAN TOKO ====== */
    const WA_TOKO = '6281234567890';   // nomor WhatsApp toko, format 62...
    const ONGKIR = 8000;               // ongkos kirim flat
    const STORAGE_KEY = 'toko_atk_keranjang';

    const produkList = [
        { id: 1, nama: 'Pulpen Gel Hitam 0.5mm', satuan: '1 Pak (12 Pcs)', kategori: 'Alat Tulis', harga: 24000, gambar: 'https://images.unsplash.com/photo-1585336261022-680e295ce3fe?auto=format&fit=crop&w=500&q=80' },
        { id: 2, nama: 'Kertas HVS A4 80gsm', satuan: '1 Rim (500 Lembar)', kategori: 'Kertas & Buku', harga: 54000, gambar: 'https://images.unsplash.com/photo-1586075010923-2dd4570fb338?auto=format&fit=crop&w=500&q=80' },
        { id: 3, nama: 'Buku Tulis Campus 58 Lbr', satuan: '1 Pak (10 Buku)', kategori: 'Kertas & Buku', harga: 42000, gambar: 'https://images.unsplash.com/photo-1531346878377-a5be20888e57?auto=format&fit=crop&w=500&q=80' },
        { id: 4, nama: 'Stapler HD-10 + Isi', satuan: '1 Set', kategori: 'Kantor & Arsip', harga: 17500, gambar: 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?auto=format&fit=crop&w=500&q=80' },
        { id: 5, nama: 'Sticky Notes Warna 3x3"', satuan: '1 Pad (100 Lembar)', kategori: 'Kertas & Buku', harga: 11000, gambar: 'https://images.unsplash.com/photo-1586281380349-632531db7ed4?auto=format&fit=crop&w=500&q=80' },
        { id: 6, nama: 'Map Plastik Kancing Folio', satuan: '1 Lusin (12 Pcs)', kategori: 'Kantor & Arsip', harga: 29000, gambar: 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=500&q=80' }
    ];

    const metodeBayar = [
        {
            id: 'transfer', nama: 'Transfer Bank', ket: 'BCA, verifikasi manual via WhatsApp',
            instruksi: { judul: 'Transfer ke rekening berikut', nomor: '1234567890', detail: 'Bank BCA a.n. Toko ATK' }
        },
        {
            id: 'qris', nama: 'QRIS', ket: 'Semua e-wallet & mobile banking',
            instruksi: { judul: 'Scan QRIS di bawah ini', qris: '/qris.png' }
        },
        {
            id: 'ewallet', nama: 'E-Wallet', ket: 'GoPay, OVO, DANA',
            instruksi: { judul: 'Kirim ke GoPay / OVO / DANA', nomor: '0812-3456-7890', detail: 'a.n. Toko ATK' }
        },
        {
            id: 'cod', nama: 'Bayar di tempat', ket: 'Tunai ke kurir atau di kasir toko',
            instruksi: { judul: 'Bayar saat pesanan diterima', detail: 'Siapkan uang pas sesuai total pesanan.' }
        }
    ];

    /* ====== STATE (Sinkron lintas halaman lewat localStorage) ====== */
    const keranjang = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');

    function simpanKeranjang() {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(keranjang));
    }

    /* ====== UTILITAS ====== */
    const formatRp = angka => 'Rp ' + angka.toLocaleString('id-ID');
    const $ = id => document.getElementById(id);

    function esc(teks) {
        const d = document.createElement('div');
        d.textContent = teks;
        return d.innerHTML;
    }

    function hitungSubtotal() {
        return keranjang.reduce((sum, i) => sum + i.harga * i.qty, 0);
    }

    function hitungDiskon(sub) {
        return sub >= 50000 ? 5000 : 0;
    }

    function tampilToast(pesan) {
        let t = $('toast');
        if (!t) {
            t = document.createElement('div');
            t.id = 'toast';
            Object.assign(t.style, {
                position: 'fixed', bottom: '24px', right: '24px',
                background: '#0f172a', color: '#fff', padding: '10px 16px',
                borderRadius: '10px', fontSize: '13px', fontWeight: '500',
                boxShadow: '0 8px 20px rgba(15,23,42,0.2)', zIndex: '999',
                opacity: '0', transition: 'opacity 0.2s', pointerEvents: 'none'
            });
            document.body.appendChild(t);
        }
        t.textContent = pesan;
        t.style.opacity = '1';
        clearTimeout(t._timer);
        t._timer = setTimeout(() => { t.style.opacity = '0'; }, 2200);
    }

    /* ====== KERANJANG ====== */
    function tambahItem(id) {
        const produk = produkList.find(p => p.id === id);
        if (!produk) return;
        const ada = keranjang.find(c => c.id === id);
        if (ada) ada.qty++;
        else keranjang.push({ ...produk, qty: 1 });
        simpanKeranjang();
        updateCartUI();
        tampilToast(`${produk.nama} ditambahkan ke keranjang`);
    }

    function tambahPaketHemat() {
        const paketId = 99;
        const ada = keranjang.find(c => c.id === paketId);
        if (ada) ada.qty++;
        else keranjang.push({
            id: paketId, nama: 'Paket Nugas Lengkap', satuan: '1 Paket',
            kategori: 'Paket Hemat', harga: 28000,
            gambar: 'https://images.unsplash.com/photo-1456735190827-d1262f71b8a3?auto=format&fit=crop&w=500&q=80',
            qty: 1
        });
        simpanKeranjang();
        updateCartUI();
        tampilToast('Paket Nugas ditambahkan ke keranjang');
    }

    function ubahQty(id, delta) {
        const idx = keranjang.findIndex(c => c.id === id);
        if (idx === -1) return;
        keranjang[idx].qty += delta;
        if (keranjang[idx].qty <= 0) keranjang.splice(idx, 1);
        simpanKeranjang();
        updateCartUI();
    }

    function hapusItem(id) {
        const idx = keranjang.findIndex(c => c.id === id);
        if (idx === -1) return;
        keranjang.splice(idx, 1);
        simpanKeranjang();
        updateCartUI();
    }

    function updateCartUI() {
        const totalQty = keranjang.reduce((sum, i) => sum + i.qty, 0);

        // 1. Update badge di navbar (semua halaman)
        const badge = document.querySelector('.badge-cart');
        if (badge) badge.textContent = totalQty;

        // 2. Render halaman keranjang jika sedang berada di /keranjang
        const cartBox = document.querySelector('.cart-items-box');
        if (cartBox) {
            const sub = hitungSubtotal();
            const diskon = hitungDiskon(sub);
            const totalBayar = Math.max(0, sub - diskon);

            const subtitle = document.querySelector('.cart-subtitle');
            if (subtitle) subtitle.textContent = `${totalQty} produk dipilih`;

            if (keranjang.length === 0) {
                cartBox.innerHTML = `
                    <div class="cart-item" style="justify-content:center; padding:36px; text-align:center;">
                        <div>
                            <p style="color:#64748b; margin-bottom:10px;">Keranjang masih kosong.</p>
                            <a href="/" class="back-link">&larr; Mulai pilih alat tulis</a>
                        </div>
                    </div>`;
            } else {
                cartBox.innerHTML = keranjang.map(item => `
                    <div class="cart-item">
                        <img src="${esc(item.gambar || '')}" alt="${esc(item.nama)}" class="cart-item-img">
                        <div class="cart-item-info">
                            <span class="card-cat">${esc(item.kategori || 'ATK')}</span>
                            <h4 class="cart-item-title">${esc(item.nama)}</h4>
                            <p class="cart-item-unit">${esc(item.satuan || '')} • ${formatRp(item.harga)}</p>
                        </div>
                        <div class="cart-item-actions">
                            <div class="qty-control">
                                <button type="button" class="qty-btn" data-aksi="kurang" data-id="${item.id}">-</button>
                                <input type="text" class="qty-input" value="${item.qty}" readonly>
                                <button type="button" class="qty-btn" data-aksi="tambah" data-id="${item.id}">+</button>
                            </div>
                            <span class="cart-item-subtotal">${formatRp(item.harga * item.qty)}</span>
                            <button type="button" class="btn-hapus" data-aksi="hapus" data-id="${item.id}">Hapus</button>
                        </div>
                    </div>
                `).join('');
            }

            const summaryBox = document.querySelector('.cart-summary');
            if (summaryBox) {
                const sisaPromo = Math.max(0, 50000 - sub);
                const promoHint = diskon > 0
                    ? `<p class="promo-hint">Selamat! Kamu dapat potongan Rp 5.000.</p>`
                    : `<p class="promo-hint" style="background:#f8fafc; border-color:#e2e8f0; color:#64748b;">Belanja ${formatRp(sisaPromo)} lagi untuk dapat potongan Rp 5.000.</p>`;

                summaryBox.innerHTML = `
                    <h3>Ringkasan Belanja</h3>
                    <div class="summary-row"><span>Total Harga (${totalQty} barang)</span><span>${formatRp(sub)}</span></div>
                    <div class="summary-row discount"><span>Promo Min. Rp 50.000</span><span>- ${formatRp(diskon)}</span></div>
                    ${promoHint}
                    <hr class="summary-divider">
                    <div class="summary-total"><span>Total Bayar</span><span>${formatRp(totalBayar)}</span></div>
                    ${keranjang.length > 0
                        ? `<a href="/checkout" class="btn-checkout">Lanjut ke Checkout &rarr;</a>`
                        : `<span class="btn-checkout" style="background:#94a3b8; cursor:not-allowed;">Keranjang Kosong</span>`}
                `;
            }
        }
    }

    /* ====== KATALOG BERANDA ====== */
    function initBeranda() {
        const grid = document.querySelector('.produk-grid');
        if (!grid) return;

        let kategoriAktif = 'Semua';
        const inputCari = $('inputCari');
        const judulKatalog = document.querySelector('.catalog-title');
        const infoJumlah = document.querySelector('.jumlah-produk');
        const chips = document.querySelectorAll('.cat-chip');

        function renderProduk() {
            const kataKunci = (inputCari ? inputCari.value : '').toLowerCase().trim();
            const hasil = produkList.filter(p => {
                const cocokKat = kategoriAktif === 'Semua' || p.kategori === kategoriAktif;
                const cocokCari = p.nama.toLowerCase().includes(kataKunci) || p.kategori.toLowerCase().includes(kataKunci);
                return cocokKat && cocokCari;
            });

            if (judulKatalog) judulKatalog.textContent = kategoriAktif === 'Semua' ? 'Semua Barang' : kategoriAktif;
            if (infoJumlah) infoJumlah.textContent = `Menampilkan ${hasil.length} barang`;

            if (hasil.length === 0) {
                grid.innerHTML = '<p style="grid-column:1/-1; text-align:center; color:#94a3b8; padding:40px 0;">Barang tidak ditemukan.</p>';
                return;
            }

            grid.innerHTML = hasil.map(p => `
                <div class="card">
                    <img src="${esc(p.gambar)}" alt="${esc(p.nama)}" class="card-img">
                    <div class="card-body">
                        <span class="card-cat">${esc(p.kategori)}</span>
                        <h4 class="card-title">${esc(p.nama)}</h4>
                        <p class="card-unit">${esc(p.satuan)}</p>
                        <div class="card-footer">
                            <span class="card-price">${formatRp(p.harga)}</span>
                            <button class="btn-beli" type="button" data-id="${p.id}">+ Beli</button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        chips.forEach(chip => {
            chip.addEventListener('click', () => {
                kategoriAktif = chip.dataset.kat || 'Semua';
                chips.forEach(c => c.classList.remove('active'));
                chip.classList.add('active');
                renderProduk();
            });
        });

        if (inputCari) inputCari.addEventListener('input', renderProduk);

        grid.addEventListener('click', e => {
            const btn = e.target.closest('.btn-beli');
            if (btn) tambahItem(Number(btn.dataset.id));
        });

        const promoBox = document.querySelector('.promo-info-box');
        if (promoBox) {
            promoBox.style.cursor = 'pointer';
            promoBox.title = 'Klik untuk menambahkan Paket Nugas ke keranjang';
            promoBox.addEventListener('click', tambahPaketHemat);
        }

        window.renderProduk = renderProduk;
        renderProduk();
    }

    /* ====== CHECKOUT ====== */
    function renderMetodeBayar() {
        const wadah = $('listBayar');
        if (!wadah) return;
        wadah.innerHTML = metodeBayar.map(m => `
            <label class="opsi">
                <input type="radio" name="bayar" value="${m.id}">
                <span><span class="opsi-nama">${m.nama}</span><span class="opsi-ket">${m.ket}</span></span>
            </label>
        `).join('');
    }

    function ambilPilihan(nama) {
        const el = document.querySelector(`input[name="${nama}"]:checked`);
        return el ? el.value : null;
    }

    function hitungRingkasan() {
        if (!$('tahapForm')) return;
        const kirim = ambilPilihan('pengambilan') === 'kirim';
        const sub = hitungSubtotal();
        const diskon = hitungDiskon(sub);
        const ongkir = kirim ? ONGKIR : 0;
        const total = Math.max(0, sub - diskon + ongkir);

        $('grupAlamat').style.display = kirim ? 'block' : 'none';
        $('ketOngkir').textContent = '+ ' + formatRp(ONGKIR);
        $('rSubtotal').textContent = formatRp(sub);
        if ($('rDiskon')) $('rDiskon').textContent = '- ' + formatRp(diskon);
        $('rOngkir').textContent = kirim ? formatRp(ongkir) : 'Gratis';
        $('rTotal').textContent = formatRp(total);
    }

    function cekField(inputId, errId, valid) {
        const input = inputId ? $(inputId) : null;
        if (input) input.classList.toggle('error', !valid);
        $(errId).classList.toggle('show', !valid);
        return valid;
    }

    function buatNomorPesanan() {
        const d = new Date();
        const tgl = String(d.getFullYear()).slice(2)
            + String(d.getMonth() + 1).padStart(2, '0')
            + String(d.getDate()).padStart(2, '0');
        return 'ATK-' + tgl + '-' + Math.floor(100 + Math.random() * 900);
    }

    function prosesPesanan(e) {
        if (e) e.preventDefault();
        if (keranjang.length === 0) return tampilToast('Keranjang masih kosong');

        const nama = $('namaPemesan').value.trim();
        const wa = $('noWa').value.replace(/[\s\-+]/g, '');
        const kirim = ambilPilihan('pengambilan') === 'kirim';
        const alamat = $('alamat').value.trim();
        const bayarId = ambilPilihan('bayar');

        const okNama = cekField('namaPemesan', 'errNama', nama.length > 0);
        const okWa = cekField('noWa', 'errWa', /^\d{9,14}$/.test(wa));
        const okAlamat = cekField('alamat', 'errAlamat', !kirim || alamat.length > 0);
        const okBayar = cekField(null, 'errBayar', !!bayarId);

        if (!(okNama && okWa && okAlamat && okBayar)) {
            return tampilToast('Lengkapi data yang bertanda merah');
        }

        const sub = hitungSubtotal();
        const diskon = hitungDiskon(sub);
        const ongkir = kirim ? ONGKIR : 0;
        const total = Math.max(0, sub - diskon + ongkir);

        const pesanan = {
            nomor: buatNomorPesanan(),
            nama, wa, kirim, alamat,
            catatan: $('catatanPesanan').value.trim(),
            metode: metodeBayar.find(m => m.id === bayarId),
            item: keranjang.map(i => ({ id: i.id, nama: i.nama, qty: i.qty, harga: i.harga })),
            sub, diskon, ongkir, total
        };

        tampilHasil(pesanan);

        keranjang.length = 0;
        simpanKeranjang();
        $('namaPemesan').value = '';
        $('noWa').value = '';
        $('alamat').value = '';
        $('catatanPesanan').value = '';
        updateCartUI();
    }

    function tampilHasil(p) {
        const ins = p.metode.instruksi;
        let kotakBayar = `<strong>${esc(ins.judul)}</strong>`;
        if (ins.nomor) kotakBayar += `<div class="nomor">${esc(ins.nomor)}</div>`;
        if (ins.detail) kotakBayar += `<div>${esc(ins.detail)}</div>`;
        if (ins.qris) {
            kotakBayar += `<div class="qris-box"><img src="${esc(ins.qris)}" alt="QRIS Toko ATK"
                onerror="this.parentNode.textContent='Pasang gambar QRIS toko di public/qris.png'"></div>`;
        }
        if (p.metode.id !== 'cod') {
            kotakBayar += `<div style="margin-top:8px;">Nominal: <strong>${formatRp(p.total)}</strong></div>`;
        }

        $('isiHasil').innerHTML = `
            <div class="hasil-ikon">&#10003;</div>
            <div class="hasil-judul">Pesanan dibuat</div>
            <p class="hasil-sub">Terima kasih, ${esc(p.nama)}. Nomor pesanan: <strong>${p.nomor}</strong></p>

            <div class="kotak-info">${kotakBayar}</div>

            <div class="ringkasan">
                ${p.item.map(i => `<div class="ringkasan-row"><span>${esc(i.nama)} &times; ${i.qty}</span><span>${formatRp(i.harga * i.qty)}</span></div>`).join('')}
                ${p.diskon > 0 ? `<div class="ringkasan-row discount"><span>Promo Min. Rp 50.000</span><span>- ${formatRp(p.diskon)}</span></div>` : ''}
                <div class="ringkasan-row"><span>Ongkos kirim</span><span>${p.kirim ? formatRp(p.ongkir) : 'Gratis'}</span></div>
                <div class="ringkasan-row total"><span>Total</span><span>${formatRp(p.total)}</span></div>
            </div>
        `;

        const baris = [
            `Halo Toko ATK, saya ingin konfirmasi pesanan *${p.nomor}*`,
            `Nama: ${p.nama}`,
            `No. WA: ${p.wa}`,
            '',
            ...p.item.map(i => `- ${i.nama} x${i.qty} = ${formatRp(i.harga * i.qty)}`),
            '',
            p.kirim ? `Dikirim ke: ${p.alamat}` : 'Diambil di toko',
            `Pembayaran: ${p.metode.nama}`,
            p.diskon > 0 ? `Diskon: -${formatRp(p.diskon)}` : null,
            `Total: ${formatRp(p.total)}`
        ].filter(Boolean);
        if (p.catatan) baris.push(`Catatan: ${p.catatan}`);
        $('linkWa').href = 'https://wa.me/' + WA_TOKO + '?text=' + encodeURIComponent(baris.join('\n'));

        $('tahapForm').style.display = 'none';
        $('tahapHasil').style.display = 'block';
    }

    /* ====== INISIALISASI SAAT HALAMAN SIAP ====== */
    document.addEventListener('DOMContentLoaded', () => {
        updateCartUI();
        initBeranda();

        // Event tombol +/- & Hapus di halaman Keranjang
        const cartBox = document.querySelector('.cart-items-box');
        if (cartBox) {
            cartBox.addEventListener('click', e => {
                const btn = e.target.closest('[data-aksi]');
                if (!btn) return;
                const id = Number(btn.dataset.id);
                if (btn.dataset.aksi === 'tambah') ubahQty(id, 1);
                if (btn.dataset.aksi === 'kurang') ubahQty(id, -1);
                if (btn.dataset.aksi === 'hapus') hapusItem(id);
            });
        }

        // Event form di halaman Checkout
        const formCheckout = $('tahapForm');
        if (formCheckout) {
            renderMetodeBayar();
            hitungRingkasan();
            document.querySelectorAll('input[name="pengambilan"]').forEach(r => {
                r.addEventListener('change', hitungRingkasan);
            });
            formCheckout.addEventListener('submit', prosesPesanan);
        }
    });

    Object.assign(window, {
        keranjang, tambahItem, tambahPaketHemat, ubahQty, hapusItem,
        hitungRingkasan, prosesPesanan
    });
})();