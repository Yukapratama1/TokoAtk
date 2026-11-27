<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok ATK - Laravel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    <div class="max-w-7xl mx-auto p-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Manajemen Stok ATK</h1>
                <p class="text-sm text-slate-500">Halaman CRUD inventaris barang Toko ATK</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('home') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-800 text-sm font-medium px-4 py-2.5 rounded-lg">
                    &larr; Ke Etalase
                </a>
                <button type="button" onclick="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg">
                    + Tambah Barang
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-xl border border-slate-200">
                <p class="text-sm text-slate-500">Total Jenis Barang</p>
                <p class="text-2xl font-bold mt-1">{{ $totalJenis }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200">
                <p class="text-sm text-slate-500">Total Unit Stok</p>
                <p class="text-2xl font-bold mt-1">{{ $totalUnit }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200">
                <p class="text-sm text-slate-500">Stok Menipis (&le; 15)</p>
                <p class="text-2xl font-bold text-amber-600 mt-1">{{ $stokMenipis }}</p>
            </div>
        </div>

        <form method="GET" action="{{ route('atk.index') }}" class="bg-white p-4 rounded-xl border border-slate-200 flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama barang..."
                class="w-full sm:w-72 px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">

            <select name="kategori_id" class="px-3 py-2 text-sm border border-slate-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Kategori</option>
                @foreach ($kategoris as $kat)
                    <option value="{{ $kat->id }}" @selected(request('kategori_id') == $kat->id)>{{ $kat->nama_kategori }}</option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-sm px-4 py-2 rounded-lg">Filter</button>
                <a href="{{ route('atk.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm px-4 py-2 rounded-lg">Reset</a>
            </div>
        </form>

        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-200 text-slate-600 text-xs uppercase">
                            <th class="py-3 px-4">Kode</th>
                            <th class="py-3 px-4">Nama Barang</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4 text-right">Harga</th>
                            <th class="py-3 px-4 text-center">Stok</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($items as $item)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-4 font-mono text-xs">{{ $item->kode_produk }}</td>
                                <td class="py-3 px-4 font-medium">{{ $item->nama_produk }}</td>
                                <td class="py-3 px-4">{{ $item->nama_kategori }}</td>
                                <td class="py-3 px-4 text-right font-medium">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="py-3 px-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $item->stok <= 15 ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                        {{ $item->stok }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center space-x-2">
                                    <button type="button"
                                        onclick='openEditModal(@json($item))'
                                        class="text-blue-600 hover:underline text-xs font-medium">
                                        Edit
                                    </button>
                                    <form action="{{ route('atk.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus barang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-xs font-medium">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">Belum ada data barang ATK.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-200">
                {{ $items->links() }}
            </div>
        </div>
    </div>

    <div id="atkModal" class="fixed inset-0 bg-slate-900/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                <h2 id="modalTitle" class="font-bold text-lg">Tambah Barang ATK</h2>
                <button type="button" onclick="closeAtkModal()" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form id="atkForm" method="POST" action="{{ route('atk.store') }}" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-600 mb-1">Kode Produk</label>
                        <input type="text" name="kode_produk" id="kode_produk" placeholder="ATK-007" required class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-600 mb-1">Kategori</label>
                        <select name="kategori_id" id="kategori_id" required class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg bg-white">
                            @foreach ($kategoris as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-slate-600 mb-1">Nama Produk</label>
                    <input type="text" name="nama_produk" id="nama_produk" required class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-600 mb-1">Harga (Rp)</label>
                        <input type="number" name="harga" id="harga" min="1" required class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-600 mb-1">Stok</label>
                        <input type="number" name="stok" id="stok" min="0" required class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg">
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" onclick="closeAtkModal()" class="px-4 py-2 text-sm bg-slate-100 hover:bg-slate-200 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCreateModal() {
            document.getElementById('modalTitle').innerText = 'Tambah Barang ATK';
            document.getElementById('atkForm').action = "{{ route('atk.store') }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('atkForm').reset();
            document.getElementById('atkModal').classList.remove('hidden');
            document.getElementById('atkModal').classList.add('flex');
        }

        function openEditModal(item) {
            document.getElementById('modalTitle').innerText = 'Edit Barang ATK';
            document.getElementById('atkForm').action = `/atk/${item.id}`;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('kode_produk').value = item.kode_produk;
            document.getElementById('kategori_id').value = item.kategori_id;
            document.getElementById('nama_produk').value = item.nama_produk;
            document.getElementById('harga').value = item.harga;
            document.getElementById('stok').value = item.stok;
            document.getElementById('atkModal').classList.remove('hidden');
            document.getElementById('atkModal').classList.add('flex');
        }

        function closeAtkModal() {
            document.getElementById('atkModal').classList.add('hidden');
            document.getElementById('atkModal').classList.remove('flex');
        }
    </script>
</body>
</html>