<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toko ATK — Etalase Alat Tulis Harian')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="container nav-inner">
            <a href="{{ route('home') }}" class="brand" onclick="resetFilter(event)">
                <span class="brand-dot"></span>
                <span>Toko ATK</span>
            </a>

            <div class="search-box">
                <input type="text" id="inputCari" placeholder="Cari pulpen, kertas HVS, buku..." oninput="renderProduk()">
            </div>

            <button class="btn-keranjang" onclick="bukaKeranjang()">
                Keranjang <span class="badge-cart" id="badgeCart">0</span>
            </button>
        </div>
    </nav>

    <!-- Konten Utama -->
    <main class="container main-content">
        @yield('content')
    </main>

    <!-- Komponen Keranjang Slide-over -->
    @include('partials.cart-drawer')

    <!-- Toast Notifikasi -->
    <div class="toast" id="toast">Pesanan dicatat!</div>

    <script src="{{ asset('js/app-atk.js') }}"></script>
</body>
</html>