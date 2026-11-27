<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AtkStockController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = DB::table('kategori')->orderBy('id', 'asc')->get();

        $baseQuery   = DB::table('produk')->whereNull('deleted_at');
        $totalJenis  = (clone $baseQuery)->count();
        $totalUnit   = (clone $baseQuery)->sum('stok');
        $stokMenipis = (clone $baseQuery)->where('stok', '<=', 15)->count();

        $query = DB::table('produk')
            ->join('kategori', 'produk.kategori_id', '=', 'kategori.id')
            ->whereNull('produk.deleted_at')
            ->select('produk.*', 'kategori.nama_kategori');

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(produk.kode_produk) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(produk.nama_produk) LIKE ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('produk.kategori_id', $request->kategori_id);
        }

        $items = $query->orderBy('produk.id', 'asc')->paginate(10)->withQueryString();

        return view('atkstock', compact('kategoris', 'items', 'totalJenis', 'totalUnit', 'stokMenipis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|integer|exists:kategori,id',
            'kode_produk' => 'required|string|max:20|unique:produk,kode_produk',
            'nama_produk' => 'required|string|max:100',
            'harga'       => 'required|integer|min:1',
            'stok'        => 'required|integer|min:0',
        ]);

        DB::table('produk')->insert([
            'kategori_id' => $validated['kategori_id'],
            'kode_produk' => trim($validated['kode_produk']),
            'nama_produk' => trim($validated['nama_produk']),
            'harga'       => $validated['harga'],
            'stok'        => $validated['stok'],
            'created_at'  => now(),
        ]);

        return redirect()->route('atk.index')->with('success', 'Barang ATK berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|integer|exists:kategori,id',
            'kode_produk' => 'required|string|max:20|unique:produk,kode_produk,' . $id,
            'nama_produk' => 'required|string|max:100',
            'harga'       => 'required|integer|min:1',
            'stok'        => 'required|integer|min:0',
        ]);

        DB::table('produk')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->update([
                'kategori_id' => $validated['kategori_id'],
                'kode_produk' => trim($validated['kode_produk']),
                'nama_produk' => trim($validated['nama_produk']),
                'harga'       => $validated['harga'],
                'stok'        => $validated['stok'],
            ]);

        return redirect()->route('atk.index')->with('success', 'Data barang ATK berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DB::table('produk')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->update([
                'deleted_at' => now(),
            ]);

        return redirect()->route('atk.index')->with('success', 'Barang ATK berhasil dihapus.');
    }
}