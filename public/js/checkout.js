/* checkout.js — keranjang + checkout + pembayaran
 *
 * KONTRAK dengan halaman katalog (welcome.blade.php):
 *  - Katalog HARUS punya variabel global `produkList` (array {id, nama, harga, ...}).
 *  - Katalog memanggil fungsi ini dari tombolnya:
 *      tambahItem(id), ubahQty(id, delta), tambahPaketHemat(), bukaKeranjang()
 *  - Katalog boleh membaca `keranjang` (array item {id, nama, harga, qty}) untuk
 *    menampilkan jumlah di kartu produk. Jangan di-assign ulang, cukup dibaca.
 *  - Setiap keranjang berubah, file ini memanggil renderProduk() milik katalog (kalau ada)
 *    dan mengisi elemen #badgeCart di navbar katalog.
 */
(function () {
    'use strict';

    /* ====== PENGATURAN TOKO (ganti sesuai data toko) ====== */
    const WA_TOKO = '6281234567890';   // nomor WhatsApp toko, format 62...
    const ONGKIR = 8000;               // ongkos kirim flat

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

    /* ====== STATE ====== */
    const keranjang = [];   // diisi/dikosongkan di tempat (tidak pernah di-assign ulang)

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

    function tampilToast(pesan) {
        const t = $('toast');
        t.textContent = pesan;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 2500);
    }

    /* ====== KERANJANG ====== */
    function tambahItem(id) {
        const produk = produkList.find(p => p.id === id);
        if (!produk) return;
        const ada = keranjang.find(c => c.id === id);
        if (ada) ada.qty++;
        else keranjang.push({ ...produk, qty: 1 });
        updateCartUI();
    }

    function tambahPaketHemat() {
        const paketId = 99;
        const ada = keranjang.find(c => c.id === paketId);
        if (ada) ada.qty++;
        else keranjang.push({ id: paketId, nama: 'Paket Nugas Lengkap', satuan: '1 Paket', harga: 28000, qty: 1 });
        updateCartUI();
        tampilToast('Paket Nugas ditambahkan ke keranjang');
    }

    function ubahQty(id, delta) {
        const idx = keranjang.findIndex(c => c.id === id);
        if (idx === -1) return;
        keranjang[idx].qty += delta;
        if (keranjang[idx].qty <= 0) keranjang.splice(idx, 1);
        updateCartUI();
    }

    function updateCartUI() {
        const totalQty = keranjang.reduce((sum, i) => sum + i.qty, 0);

        const badge = $('badgeCart');   // ada di navbar milik katalog
        if (badge) badge.textContent = totalQty;
        $('totalHarga').textContent = formatRp(hitungSubtotal());

        const wadah = $('isiKeranjang');
        if (keranjang.length === 0) {
            wadah.innerHTML = '<p class="empty-msg">Keranjang masih kosong.</p>';
        } else {
            wadah.innerHTML = keranjang.map(item => `
                <div class="cart-item">
                    <div>
                        <div class="cart-item-title">${esc(item.nama)}</div>
                        <div class="cart-item-price">${formatRp(item.harga)} &times; ${item.qty}</div>
                    </div>
                    <div class="qty-control">
                        <button onclick="ubahQty(${item.id}, -1)">-</button>
                        <span>${item.qty}</span>
                        <button onclick="ubahQty(${item.id}, 1)">+</button>
                    </div>
                </div>
            `).join('');
        }

        if (typeof window.renderProduk === 'function') window.renderProduk();
        if ($('modalCheckout').classList.contains('show')) hitungRingkasan();
    }

    function bukaKeranjang() {
        $('overlay').classList.add('show');
        $('drawer').classList.add('open');
    }

    function tutupKeranjang() {
        $('overlay').classList.remove('show');
        $('drawer').classList.remove('open');
    }

    /* ====== CHECKOUT ====== */
    function renderMetodeBayar() {
        $('listBayar').innerHTML = metodeBayar.map(m => `
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
        const kirim = ambilPilihan('pengambilan') === 'kirim';
        const sub = hitungSubtotal();
        const ongkir = kirim ? ONGKIR : 0;

        $('grupAlamat').style.display = kirim ? 'block' : 'none';
        $('ketOngkir').textContent = '+ ' + formatRp(ONGKIR);
        $('rSubtotal').textContent = formatRp(sub);
        $('rOngkir').textContent = kirim ? formatRp(ongkir) : 'Gratis';
        $('rTotal').textContent = formatRp(sub + ongkir);
    }

    function bukaCheckout() {
        if (keranjang.length === 0) return tampilToast('Keranjang masih kosong');
        tutupKeranjang();
        renderMetodeBayar();
        $('tahapForm').style.display = 'flex';
        $('tahapHasil').style.display = 'none';
        document.querySelectorAll('#modalCheckout .field-error').forEach(e => e.classList.remove('show'));
        document.querySelectorAll('#modalCheckout .input-form').forEach(e => e.classList.remove('error'));
        hitungRingkasan();
        $('modalCheckout').classList.add('show');
    }

    function tutupCheckout() {
        $('modalCheckout').classList.remove('show');
    }

    // Tandai field bermasalah. Mengembalikan true jika field valid.
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

    function prosesPesanan() {
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
        const ongkir = kirim ? ONGKIR : 0;

        const pesanan = {
            nomor: buatNomorPesanan(),
            nama, wa, kirim, alamat,
            catatan: $('catatanPesanan').value.trim(),
            metode: metodeBayar.find(m => m.id === bayarId),
            item: keranjang.map(i => ({ id: i.id, nama: i.nama, qty: i.qty, harga: i.harga })),
            sub, ongkir,
            total: sub + ongkir
        };

        // TODO (tahap database): kirim `pesanan` ke Laravel lewat fetch('/pesanan', { method: 'POST', ... })
        // dengan header 'X-CSRF-TOKEN'. Hasilnya nanti dipakai untuk nomor pesanan resmi.

        tampilHasil(pesanan);

        keranjang.length = 0;   // kosongkan di tempat agar referensi dari katalog tetap valid
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
            kotakBayar += `<div style="margin-top:8px;">Nominal: <strong style="display:inline;">${formatRp(p.total)}</strong></div>`;
        }

        $('isiHasil').innerHTML = `
            <div class="hasil-ikon">&#10003;</div>
            <div class="hasil-judul">Pesanan dibuat</div>
            <p class="hasil-sub">Terima kasih, ${esc(p.nama)}. Nomor pesanan: <strong>${p.nomor}</strong></p>

            <div class="kotak-info">${kotakBayar}</div>

            <div class="ringkasan">
                ${p.item.map(i => `<div class="ringkasan-row"><span>${esc(i.nama)} &times; ${i.qty}</span><span>${formatRp(i.harga * i.qty)}</span></div>`).join('')}
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
            `Total: ${formatRp(p.total)}`
        ];
        if (p.catatan) baris.push(`Catatan: ${p.catatan}`);
        $('linkWa').href = 'https://wa.me/' + WA_TOKO + '?text=' + encodeURIComponent(baris.join('\n'));

        $('tahapForm').style.display = 'none';
        $('tahapHasil').style.display = 'flex';
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            tutupCheckout();
            tutupKeranjang();
        }
    });

    /* ====== YANG DIBUKA KE LUAR (dipanggil dari HTML katalog & partial) ====== */
    Object.assign(window, {
        keranjang,
        tambahItem, tambahPaketHemat, ubahQty,
        bukaKeranjang, tutupKeranjang,
        bukaCheckout, tutupCheckout, hitungRingkasan, prosesPesanan
    });
})();