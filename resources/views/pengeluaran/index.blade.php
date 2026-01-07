@extends('layouts.app')

@section('content')
<div class="p-6 space-y-4">

  <!-- Header -->
  <div class="flex flex-wrap items-center justify-between gap-3">
    <div>
      <h2 class="text-2xl font-bold text-[#388087]">Pengeluaran Harian</h2>
    </div>

    <a href="{{ route('pengeluaran.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-lg"></i>
      Tambah Pengeluaran
    </a>
  </div>

  <!-- Filter -->
  <div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('pengeluaran.index') }}">
        <div class="inline-flex flex-col gap-2">
            <label class="label">Tanggal</label>
            <div class="inline-flex items-center gap-3">
            <input
                type="date"
                name="tanggal"
                value="{{ $tanggal }}"
                class="input h-[44px] w-[260px]"
>
            <button
                type="submit"
                class="btn btn-ghost h-[44px]"
            >
                <i class="bi bi-funnel"></i>
                Tampilkan
            </button>
            </div>
            <div class="help">Filter pengeluaran berdasarkan tanggal.</div>
        </div>
        </form>

    </div>
  </div>

  <!-- Table -->
  <div class="card">
    <div class="card-header flex items-center justify-between gap-3">
      <div class="font-bold">Daftar Pengeluaran</div>
      <div class="text-sm text-gray-600">
        Total: <span class="font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</span>
      </div>
    </div>

    <div class="card-body">
      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th style="width:70px;">No</th>
              <th style="width:140px;">Tanggal</th>
              <th>Kategori</th>
              <th>Deskripsi</th>
              <th>Jumlah</th>
              <th class="col-action">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($pengeluaran as $item)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->tanggal->format('d-m-Y') }}</td>
                <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $item->deskripsi }}</td>
                <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                <td class="col-action">
                  <div class="actions">
                    <a class="btn btn-ghost" href="{{ route('pengeluaran.edit', $item->id) }}">
                      <i class="bi bi-pencil-fill"></i>
                    </a>

                    <form action="{{ route('pengeluaran.destroy', $item->id) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus pengeluaran ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash3-fill"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="muted" style="padding:18px; text-align:center;">
                  <i class="bi bi-window-dash text-gray-400 text-3xl pb-4"></i><br>
                  Belum ada pengeluaran
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection
