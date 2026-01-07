<?php

namespace App\Http\Controllers;

use App\Models\KategoriPengeluaran;
use Illuminate\Http\Request;

class KategoriPengeluaranController extends Controller
{
    public function index()
    {
        $kategori = KategoriPengeluaran::orderBy('nama_kategori')->get();
        return view('kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255', 'unique:kategori_pengeluarans,nama_kategori'],
            'keterangan' => ['nullable', 'string'],
        ]);

        KategoriPengeluaran::create($data);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori pengeluaran berhasil ditambahkan.');
    }

    public function edit(KategoriPengeluaran $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, KategoriPengeluaran $kategori)
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255', 'unique:kategori_pengeluarans,nama_kategori,' . $kategori->id],
            'keterangan' => ['nullable', 'string'],
        ]);

        $kategori->update($data);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori pengeluaran berhasil diperbarui.');
    }

    public function destroy(KategoriPengeluaran $kategori)
    {
        $kategori->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    public function show(\App\Models\KategoriPengeluaran $kategori)
    {
        return view('kategori.show', compact('kategori'));
    }
}
