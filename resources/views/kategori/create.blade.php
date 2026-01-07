@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Kategori</h2>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="POST" action="{{ route('kategori.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="bi bi-tag"></i> Nama Kategori
                </label>
                <input type="text" 
                       name="nama_kategori" 
                       value="{{ old('nama_kategori') }}"
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#388087]"
                       placeholder="Masukkan nama kategori">
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" 
                        class="px-6 py-2 bg-[#388087] text-white rounded-lg hover:bg-[#2d6a70] transition flex items-center gap-2">
                    <i class="bi bi-check-circle"></i>
                    <span>Simpan</span>
                </button>
                <a href="{{ route('kategori.index') }}" 
                   class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition flex items-center gap-2">
                    <i class="bi bi-x-circle"></i>
                    <span>Batal</span>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
