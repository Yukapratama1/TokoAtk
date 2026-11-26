<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko ATK — Etalase Alat Tulis Harian</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Reset & Dasar */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            line-height: 1.5;
        }

        .container {
            max-width: 1060px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Navbar Sticky */
        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 20;
        }

        .nav-inner {
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 9px;
            text-decoration: none;
            color: #0f172a;
            font-weight: 700;
            font-size: 18px;
            white-space: nowrap;
        }

        .brand-dot {
            width: 10px;
            height: 10px;
            background-color: #2563eb;
            border-radius: 50%;
            display: inline-block;
        }

        .search-box {
            flex: 1;
            max-width: 440px;
        }

        .search-box input {
            width: 100%;
            padding: 9px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            background-color: #f8fafc;
            outline: none;
            transition: border-color 0.2s, background-color 0.2s, box-shadow 0.2s;
        }

        .search-box input:focus {
            border-color: #2563eb;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn-keranjang {
            background-color: #0f172a;
            color: #ffffff;
            border: none;
            padding: 9px 15px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .badge-cart {
            background-color: #2563eb;
            color: #ffffff;
            font-size: 12px;
            font-weight: 600;
            padding: 1px 7px;
            border-radius: 999px;
        }

        /* Konten Utama Beranda */
        .main-content {
            padding-top: 24px;
            padding-bottom: 64px;
        }

        /* 1. Banner & Promo Santai */
        .promo-banner {
            background: linear-gradient(125deg, #eff6ff 0%, #f8fafc 65%, #eef2ff 100%);
            border: 1px solid #dbeafe;
            border-radius: 16px;
            padding: 24px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 18px;
            position: relative;
            overflow: hidden;
        }

        .promo-content {
            max-width: 560px;
            z-index: 1;
        }

        .promo-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            color: #2563eb;
            background-color: #ffffff;
            border: 1px solid #bfdbfe;
            padding: 4px 11px;
            border-radius: 999px;
            margin-bottom: 12px;
        }

        .promo-content h1 {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.3;
            margin-bottom: 8px;
        }

        .promo-content p {
            font-size: 14px;
            color: #475569;
            margin-bottom: 16px;
        }

        .promo-actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn-paket {
            background-color: #2563eb;
            color: #ffffff;
            border: none;
            padding: 9px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: background-color 0.15s;
        }

        .btn-paket:hover {
            background-color: #1d4ed8;
        }

        .promo-note {
            font-size: 12px;
            color: #64748b;
        }

        .promo-visual {
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }

        .promo-visual img {
            width: 170px;
            height: 124px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px solid #ffffff;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.08);
        }

        /* 2. Deretan Chip Kategori */
        .category-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 24px;
        }

        .cat-chip {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            font-family: inherit;
            cursor: pointer;
            text-align: left;
            transition: border-color 0.15s, background-color 0.15s, box-shadow 0.15s;
        }

        .cat-chip:hover {
            border-color: #bfdbfe;
            background-color: #f8fafc;
        }

        .cat-chip.active {
            border-color: #2563eb;
            background-color: #eff6ff;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.08);
        }

        .cat-chip-name {
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
        }

        .cat-chip.active .cat-chip-name {
            color: #1d4ed8;
        }

        .cat-chip-count {
            font-size: 12px;
            font-weight: 500;
            color: #64748b;
            background-color: #f1f5f9;
            padding: 2px 8px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .cat-chip.active .cat-chip-count {
            background-color: #dbeafe;
            color: #1d4ed8;
        }

        /* 3. Header & Grid Katalog Produk */
        .catalog-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .catalog-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }

        .jumlah-produk {
            font-size: 13px;
            color: #64748b;
        }

        .produk-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.04);
        }

        .card-img {
            width: 100%;
            height: 175px;
            object-fit: cover;
            background-color: #f1f5f9;
        }

        .card-body {
            padding: 16px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .card-cat {
            font-size: 11px;
            font-weight: 600;
            color: #2563eb;
            margin-bottom: 4px;
        }

        .card-title {
            font-size: 15px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .card-unit {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 14px;
        }

        .card-footer {
            margin-top: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-price {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
        }

        .btn-beli {
            background-color: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
            padding: 6px 13px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-beli:hover {
            background-color: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }

        .empty-msg {
            text-align: center;
            color: #94a3b8;
            font-size: 14px;
            padding: 40px 0;
            grid-column: 1 / -1;
        }

        /* Responsif Mobile & Tablet */
        @media (max-width: 820px) {
            .category-strip {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 680px) {
            .promo-banner {
                flex-direction: column;
                align-items: flex-start;
                padding: 20px;
            }

            .promo-visual {
                width: 100%;
            }

            .promo-visual img {
                width: 100%;
                height: 140px;
            }

            .produk-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar Beranda -->
    <nav class="navbar">
        <div class="container nav-inner">
            <a href="{{ route('home') }}" class="brand" onclick="resetFilter(event)">
                <span class="brand-dot"></span>
                <span>Toko ATK</span>
            </a>

            <div class="search-box">
                <input type="text" id="inputCari" placeholder="Cari pulpen, kertas HVS, buku..." oninput="renderProduk()">
            </div>

            <button class="btn-keranjang" type="button">
                Keranjang <span class="badge-cart">0</span>
            </button>
        </div>
    </nav>

    <!-- Konten Utama Beranda -->
    <main class="container main-content">

        <!-- 1. Banner & Promo Santai -->
        <section class="promo-banner">
            <div class="promo-content">
                <span class="promo-tag">✨ Promo Minggu Ini</span>
                <h1>Stok alat tulis aman, nugas & kerja makin lancar</h1>
                <p>Potongan <strong>Rp 5.000</strong> otomatis untuk belanja min. Rp 50.000 — atau langsung ambil Paket Nugas hemat siap pakai.</p>
                <div class="promo-actions">
                    <button class="btn-paket" type="button">+ Ambil Paket Nugas (Rp 28.000)</button>
                    <span class="promo-note">Isi: 2 Buku Campus + 3 Pulpen Gel + 1 Tipe-X</span>
                </div>
            </div>
            <div class="promo-visual">
                <img src="https://images.unsplash.com/photo-1456735190827-d1262f71b8a3?auto=format&fit=crop&w=600&q=80" alt="Perlengkapan Alat Tulis Harian">
            </div>
        </section>

        <!-- 2. Deretan Chip Kategori -->
        <section class="category-strip">
            <button class="cat-chip active" data-kat="Semua" onclick="pilihKategori('Semua')">
                <span class="cat-chip-name">Semua Barang</span>
                <span class="cat-chip-count" id="countSemua">6 item</span>
            </button>
            <button class="cat-chip" data-kat="Alat Tulis" onclick="pilihKategori('Alat Tulis')">
                <span class="cat-chip-name">Alat Tulis</span>
                <span class="cat-chip-count" id="countAlatTulis">1 item</span>
            </button>
            <button class="cat-chip" data-kat="Kertas & Buku" onclick="pilihKategori('Kertas & Buku')">
                <span class="cat-chip-name">Kertas & Buku</span>
                <span class="cat-chip-count" id="countKertas">3 item</span>
            </button>
            <button class="cat-chip" data-kat="Kantor & Arsip" onclick="pilihKategori('Kantor & Arsip')">
                <span class="cat-chip-name">Kantor & Arsip</span>
                <span class="cat-chip-count" id="countKantor">2 item</span>
            </button>
        </section>

        <!-- 3. Header & Grid Katalog Produk -->
        <div class="catalog-header">
            <h2 class="catalog-title" id="judulKatalog">Semua Barang</h2>
            <span class="jumlah-produk" id="infoJumlah">Menampilkan 6 barang</span>
        </div>

        <div class="produk-grid" id="gridProduk"></div>
    </main>

    <script>
        const produkList = [
            { id: 1, nama: 'Pulpen Gel Hitam 0.5mm', satuan: '1 Pak (12 Pcs)', kategori: 'Alat Tulis', harga: 24000, gambar: 'https://images.unsplash.com/photo-1585336261022-680e295ce3fe?auto=format&fit=crop&w=500&q=80' },
            { id: 2, nama: 'Kertas HVS A4 80gsm', satuan: '1 Rim (500 Lembar)', kategori: 'Kertas & Buku', harga: 54000, gambar: 'https://images.unsplash.com/photo-1586075010923-2dd4570fb338?auto=format&fit=crop&w=500&q=80' },
            { id: 3, nama: 'Buku Tulis Campus 58 Lbr', satuan: '1 Pak (10 Buku)', kategori: 'Kertas & Buku', harga: 42000, gambar: 'https://images.unsplash.com/photo-1531346878377-a5be20888e57?auto=format&fit=crop&w=500&q=80' },
            { id: 4, nama: 'Stapler HD-10 + Isi', satuan: '1 Set', kategori: 'Kantor & Arsip', harga: 17500, gambar: 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?auto=format&fit=crop&w=500&q=80' },
            { id: 5, nama: 'Sticky Notes Warna 3x3"', satuan: '1 Pad (100 Lembar)', kategori: 'Kertas & Buku', harga: 11000, gambar: 'https://images.unsplash.com/photo-1586281380349-632531db7ed4?auto=format&fit=crop&w=500&q=80' },
            { id: 6, nama: 'Map Plastik Kancing Folio', satuan: '1 Lusin (12 Pcs)', kategori: 'Kantor & Arsip', harga: 29000, gambar: 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=500&q=80' }
        ];

        let kategoriAktif = 'Semua';

        function formatRp(angka) {
            return 'Rp ' + angka.toLocaleString('id-ID');
        }

        function pilihKategori(kat) {
            kategoriAktif = kat;
            document.querySelectorAll('.cat-chip').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.kat === kat);
            });
            document.getElementById('judulKatalog').textContent = kat === 'Semua' ? 'Semua Barang' : kat;
            renderProduk();
        }

        function resetFilter(e) {
            if (e) e.preventDefault();
            document.getElementById('inputCari').value = '';
            pilihKategori('Semua');
        }

        function renderProduk() {
            const kataKunci = document.getElementById('inputCari').value.toLowerCase().trim();
            const grid = document.getElementById('gridProduk');

            const hasil = produkList.filter(p => {
                const cocokKat = kategoriAktif === 'Semua' || p.kategori === kategoriAktif;
                const cocokCari = p.nama.toLowerCase().includes(kataKunci) || p.kategori.toLowerCase().includes(kataKunci);
                return cocokKat && cocokCari;
            });

            document.getElementById('infoJumlah').textContent = 'Menampilkan ' + hasil.length + ' barang';

            if (hasil.length === 0) {
                grid.innerHTML = '<p class="empty-msg">Barang tidak ditemukan.</p>';
                return;
            }

            grid.innerHTML = hasil.map(p => `
                <div class="card">
                    <img src="${p.gambar}" alt="${p.nama}" class="card-img">
                    <div class="card-body">
                        <span class="card-cat">${p.kategori}</span>
                        <h4 class="card-title">${p.nama}</h4>
                        <p class="card-unit">${p.satuan}</p>
                        <div class="card-footer">
                            <span class="card-price">${formatRp(p.harga)}</span>
                            <button class="btn-beli" type="button">+ Beli</button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        renderProduk();
    </script>
</body>
</html>