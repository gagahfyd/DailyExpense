@extends('layouts.app')

@section('content')
<div class="p-6 space-y-3">

    <!-- Date Range Selector -->
    <div class="flex items-center relative">
        @php $r = request('range','week'); @endphp
        
        <!-- Tombol W di kiri -->
        <div class="relative">
            <button class="range-selector-btn px-4 py-2 bg-white rounded-lg flex items-center gap-2 hover:bg-gray-50">
                <span class="text-sm font-medium">{{ strtoupper(substr($r, 0, 1)) }}</span>
                <i class="bi bi-chevron-down text-xs text-[#388087]"></i>
            </button>
            <div class="absolute top-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg z-10 hidden" id="rangeDropdown">
            <a href="{{ route('statistik.index', ['range'=>'day', 'offset' => request('offset', 0)]) }}"class="block px-4 py-2 text-sm text-[#388087] no-underline hover:no-underline hover:bg-[#388087] hover:text-[#F5F5F1] hover:rounded-lg"> Harian</a>
                <a href="{{ route('statistik.index', ['range'=>'week', 'offset' => request('offset', 0)]) }}" class="block px-4 py-2 text-sm text-[#388087] no-underline hover:no-underline hover:bg-[#388087] hover:text-[#F5F5F1] hover:rounded-lg">Mingguan</a>
                <a href="{{ route('statistik.index', ['range'=>'month', 'offset' => request('offset', 0)]) }}" class="block px-4 py-2 text-sm text-[#388087] no-underline hover:no-underline hover:bg-[#388087] hover:text-[#F5F5F1] hover:rounded-lg">Bulanan</a>
                <a href="{{ route('statistik.index', ['range'=>'year', 'offset' => request('offset', 0)]) }}" class="block px-4 py-2 text-sm text-[#388087] no-underline hover:no-underline hover:bg-[#388087] hover:text-[#F5F5F1] hover:rounded-lg">Tahunan</a>
            </div>
        </div>

        <!-- Tanggal di center -->
        <div class="flex items-center gap-2 text-[#388087] absolute left-1/2 transform -translate-x-1/2">
            <a href="{{ route('statistik.index', ['range' => $range, 'offset' => request('offset', 0) - 1]) }}" 
               class="p-1 hover:bg-gray-200 rounded transition text-[#388087]">
                <i class="bi bi-chevron-left"></i>
            </a>
            <span class="text-sm font-medium text-[#388087]">{{ $dateRange ?? '04/01 - 10/01/2026' }}</span>
            <a href="{{ route('statistik.index', ['range' => $range, 'offset' => request('offset', 0) + 1]) }}" 
               class="p-1 hover:bg-gray-200 rounded transition text-[#388087]">
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>

    <!-- Pie Chart -->
    <div class="bg-white rounded shadow-sm p-4">
        <h2 class="text-lg font-bold mb-3 text-gray-800 text-center">Statistik Pengeluaran</h2>
        <div class="flex items-center justify-center">
            <div class="w-64 h-64">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Table by category -->
    <div class="bg-white rounded shadow-sm p-4">
        <h3 class="text-lg font-bold mb-3 text-gray-800 text-center">Pengeluaran</h3>
            <div class="overflow-x-auto">
                <table class="w-full max-w-2xl mx-auto">
                    <thead>
                        <tr class="border-b">
                            <th class="text-center text-gray-800 py-3 px-4 text-sm font-semibold w-24">Persentase</th>
                            <th class="text-left text-gray-800 py-3 px-4 text-sm font-semibold">Kategori</th>
                            <th class="text-right text-gray-800 py-3 px-4 text-sm font-semibold">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($byCategory as $row)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4 text-sm text-gray-900 text-center">{{ $row->percentage }}%</td>
                                <td class="py-3 px-4 text-sm text-gray-700">{{ $row->nama_kategori }}</td>
                                <td class="py-3 px-4 text-sm text-gray-700 text-right">Rp {{ number_format($row->total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-gray-500">Belum ada data</td>
                            </tr>
                        @endforelse
                        @if($byCategory->count() > 0)
                            <tr class="bg-[#C2EDCE] font-semibold">
                                <td class="py-3 px-4 text-sm" colspan="2">Total</td>
                                <td class="py-3 px-4 text-sm text-right">Rp {{ number_format($totalAll ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
// Pie Chart Data
const pieData = @json($byCategory->map(function($item) {
    return [
        'label' => $item->nama_kategori,
        'value' => (float)$item->total,
        'percentage' => $item->percentage
    ];
}));

const pieLabels = pieData.map(d => d.label);
const pieValues = pieData.map(d => d.value);

// Color palette untuk pie chart (shades of blue)
const pieColors = [
    'rgba(59, 130, 246, 0.6)',   // Light blue
    'rgba(37, 99, 235, 0.7)',    // Medium blue
    'rgba(29, 78, 216, 0.8)',    // Darker blue
    'rgba(30, 64, 175, 0.9)',    // Dark blue
    'rgba(56, 128, 135, 0.7)',   // Teal
    'rgba(20, 184, 166, 0.6)',   // Cyan
];

const ctx = document.getElementById('pieChart');
if (ctx && pieValues.length > 0) {
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: pieLabels,
            datasets: [{
                data: pieValues,
                backgroundColor: pieColors.slice(0, pieValues.length),
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1,
            plugins: {
                legend: {
                position: 'bottom',
                align: 'center',
                labels: {
                    boxWidth: 14,
                    boxHeight: 14,
                    padding: 16,
                    font: { size: 12, weight: '500' }
                }
                },
                tooltip: {
                callbacks: {
                    label: function(context) {
                    const label = context.label || '';
                    const value = context.parsed || 0;
                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                    const percentage = ((value / total) * 100).toFixed(0);
                    return label + ': ' + percentage + '% (Rp ' + value.toLocaleString('id-ID') + ')';
                    }
                }
                }
            }
            }
    });
}

// Range dropdown toggle
document.querySelector('button').addEventListener('click', function(e) {
    e.stopPropagation();
    const dropdown = document.getElementById('rangeDropdown');
    dropdown.classList.toggle('hidden');
});

document.addEventListener('click', function() {
    document.getElementById('rangeDropdown').classList.add('hidden');
});

// Date range navigation sudah dihandle oleh link
</script>
@endpush
