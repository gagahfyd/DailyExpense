@extends('layouts.app')

@section('content')
<div class="p-6 space-y-4">

  <!-- Header (samakan dengan Pengeluaran) -->
  <div class="flex flex-wrap items-center justify-between gap-3">
    <div>
      <h2 class="text-2xl font-bold text-[#388087]">Kategori</h2>
    </div>

    <a href="{{ route('kategori.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-lg"></i>
      Tambah Kategori
    </a>
  </div>

  <!-- Card + Judul Daftar Kategori (samakan dengan Daftar Pengeluaran) -->
  <div class="card">
    <div class="card-header flex items-center justify-between gap-3">
      <div class="font-bold text-[#388087]">Daftar Kategori</div>
    </div>

    <div class="card-body">
      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th style="width:70px;">No</th>
              <th>Nama Kategori</th>
              <th class="col-action">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($kategori as $item)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nama_kategori }}</td>
                <td class="col-action">
                  <div class="actions">
                    <a href="{{ route('kategori.edit', $item->id) }}" class="btn btn-ghost btn-icon">
                      <i class="bi bi-pencil"></i>
                    </a>

                    <form action="{{ route('kategori.destroy', $item->id) }}" method="POST"
                          onsubmit="return confirm('Hapus kategori ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-icon">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="muted">Belum ada kategori.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection
