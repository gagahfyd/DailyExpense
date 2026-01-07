<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KategoriPengeluaran;

class KategoriPengeluaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Makanan',
            'Transport',
            'Belanja',
            'Tagihan',
            'Hiburan',
            'Lain-lain',
        ];

    foreach ($data as $nama) {
            KategoriPengeluaran::create([
                'nama_kategori' => $nama,
            ]);
        }
    }
}