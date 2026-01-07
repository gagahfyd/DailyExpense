<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengeluaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = Carbon::today();

        // Mulai dari 28 Desember (tahun lalu kalau sekarang Januari)
        $startDate = Carbon::create(
            $today->month === 1 ? $today->year - 1 : $today->year,
            12,
            28
        );

        $descriptionsByCategory = [
            1 => ['Kebutuhan Pokok', 'Baju'],                 // Belanja
            2 => ['Konser', 'Nonton Bioskop'],                // Hiburan
            3 => ['Keperluan Mendadak', 'Donasi'],             // Lain-lain
            4 => ['Sarapan', 'Makan Siang', 'Makan Malam'],   // Makan
            5 => ['Bayar Wifi'],                              // Tagihan
            6 => ['KRL', 'TransJogja', 'Bensin'],              // Transport
        ];

        // Range nominal per kategori (lebih realistis)
        $amountRange = [
            1 => [30_000, 150_000],  // Belanja
            2 => [50_000, 200_000],  // Hiburan
            3 => [10_000, 50_000],   // Lain-lain
            4 => [10_000, 40_000],   // Makan
            5 => [150_000, 300_000], // Tagihan
            6 => [5_000, 30_000],    // Transport
        ];

        $totalLimit = 1_000_000;
        $currentTotal = 0;

        for ($date = $startDate->copy(); $date->lte($today); $date->addDay()) {
            $transactionsToday = rand(1, 3);

            for ($i = 0; $i < $transactionsToday; $i++) {
                if ($currentTotal >= $totalLimit) {
                    return;
                }

                $categoryId = array_rand($descriptionsByCategory);
                [$min, $max] = $amountRange[$categoryId];
                $amount = rand($min, $max);

                if ($currentTotal + $amount > $totalLimit) {
                    $amount = $totalLimit - $currentTotal;
                }

                DB::table('pengeluarans')->insert([
                    'tanggal' => $date->toDateString(),
                    'kategori_id' => $categoryId,
                    'deskripsi' => $descriptionsByCategory[$categoryId][array_rand($descriptionsByCategory[$categoryId])],
                    'jumlah' => $amount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $currentTotal += $amount;
            }
        }
    }
}
