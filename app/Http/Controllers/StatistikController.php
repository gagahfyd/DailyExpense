<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatistikController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->get('range', 'week');
        $offset = (int) $request->get('offset', 0);

        [$start, $end, $groupFormat, $labelFormat] = $this->rangeConfig($range, $offset);

        // Chart data: total per group (hari/bulan)
        $chart = DB::table('pengeluarans')
            ->selectRaw("DATE_FORMAT(tanggal, '{$groupFormat}') as g, SUM(jumlah) as total")
            ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->groupBy('g')
            ->orderBy('g')
            ->get();

        $chartLabels = $chart->pluck('g')->map(function($g) use ($labelFormat) {
            // g sudah string dari DATE_FORMAT; biarkan saja supaya simpel
            return $g;
        })->toArray();

        $chartValues = $chart->pluck('total')->map(fn($v) => (float)$v)->toArray();

        // Table by category with percentage
        $byCategory = DB::table('pengeluarans')
            ->join('kategori_pengeluarans', 'pengeluarans.kategori_id', '=', 'kategori_pengeluarans.id')
            ->select('kategori_pengeluarans.nama_kategori', DB::raw('SUM(pengeluarans.jumlah) as total'))
            ->whereBetween('pengeluarans.tanggal', [$start->toDateString(), $end->toDateString()])
            ->groupBy('kategori_pengeluarans.nama_kategori')
            ->orderByDesc('total')
            ->get();

        // Calculate total and percentage for pie chart
        $totalAll = $byCategory->sum('total');
        $byCategory = $byCategory->map(function($item) use ($totalAll) {
            $item->percentage = $totalAll > 0 ? round(($item->total / $totalAll) * 100) : 0;
            return $item;
        });

        // Format date range for display
        $dateRange = $start->format('d/m') . ' - ' . $end->format('d/m/Y');

        return view('statistik.index', compact(
            'range', 'chartLabels', 'chartValues', 'byCategory', 'totalAll', 'dateRange', 'start', 'end'
        ));
    }

    // Export nanti kita isi sederhana (CSV)
    public function export(Request $request)
    {
        $range = $request->get('range', 'week');
        [$start, $end] = $this->rangeConfig($range);

        $rows = DB::table('pengeluarans')
            ->leftJoin('kategori_pengeluarans', 'pengeluarans.kategori_id', '=', 'kategori_pengeluarans.id')
            ->select('pengeluarans.tanggal', 'kategori_pengeluarans.nama_kategori', 'pengeluarans.deskripsi', 'pengeluarans.jumlah')
            ->whereBetween('pengeluarans.tanggal', [$start->toDateString(), $end->toDateString()])
            ->orderBy('pengeluarans.tanggal')
            ->get();

        $filename = "export_pengeluaran_{$range}.csv";
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
        ];

        $callback = function() use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['tanggal', 'kategori', 'deskripsi', 'jumlah']);
            foreach ($rows as $r) {
                fputcsv($out, [$r->tanggal, $r->nama_kategori ?? '-', $r->deskripsi, $r->jumlah]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function rangeConfig(string $range, int $offset = 0): array
    {
        $today = Carbon::today();

        return match ($range) {
            'day' => [
                $today->copy()->addDays($offset),
                $today->copy()->addDays($offset),
                '%Y-%m-%d',  // group per hari
                'd-m-Y'
            ],
            'month' => [
                $today->copy()->addMonths($offset)->startOfMonth(),
                $today->copy()->addMonths($offset)->endOfMonth(),
                '%Y-%m-%d',  // masih per hari biar chart detail dalam bulan
                'd-m-Y'
            ],
            'year' => [
                $today->copy()->addYears($offset)->startOfYear(),
                $today->copy()->addYears($offset)->endOfYear(),
                '%Y-%m',     // group per bulan untuk chart tahunan
                'm-Y'
            ],
            default => [ // week
                $today->copy()->addWeeks($offset)->startOfWeek(),
                $today->copy()->addWeeks($offset)->endOfWeek(),
                '%Y-%m-%d',
                'd-m-Y'
            ],
        };
    }
}
