<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use App\Models\KategoriPengeluaran;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->toDateString());

        $pengeluaran = Pengeluaran::with('kategori')
            ->whereDate('tanggal', $tanggal)
            ->latest()
            ->get();

        $total = $pengeluaran->sum('jumlah');

        return view('pengeluaran.index', compact('pengeluaran', 'total', 'tanggal'));
    }

    public function create()
    {
        $kategori = KategoriPengeluaran::orderBy('nama_kategori')->get();
        return view('pengeluaran.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'jumlah' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
            'kategori_id' => ['required', 'exists:kategori_pengeluarans,id'],
        ]);

        Pengeluaran::create($data);

        return redirect()->route('pengeluaran.index', ['tanggal' => $data['tanggal']])
            ->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        $kategori = KategoriPengeluaran::orderBy('nama_kategori')->get();
        return view('pengeluaran.edit', compact('pengeluaran', 'kategori'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'jumlah' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
            'kategori_id' => ['required', 'exists:kategori_pengeluarans,id'],
        ]);

        $pengeluaran->update($data);

        return redirect()->route('pengeluaran.index', ['tanggal' => $data['tanggal']])
            ->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $tanggal = $pengeluaran->tanggal;
        $pengeluaran->delete();

        return redirect()->route('pengeluaran.index', ['tanggal' => $tanggal])
        ->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
