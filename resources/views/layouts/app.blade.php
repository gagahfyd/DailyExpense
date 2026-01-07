<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DailyExpense</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --bg-primary: #F5F5F1;
            --bg-secondary: #C2EDCE;
            --color-teal: #388087;
        }
    </style>
</head>
<body class="bg-[#F5F5F1]">

<div class="de-app min-h-screen flex">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-[#388087] text-white flex flex-col fixed h-screen">
        <div class="h-24 flex flex-col items-center justify-center border-white/20 gap-1 mt-6">
        <img
            src="{{ asset('images/logo.png') }}"
            alt="Logo"
            class="h-10 object-contain"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
        >

        <span
            class="font-bold tracking-wide text-xl text-[#F5F5F1]"
            id="fallbackLogo"
            style="display: none;"
        >
            LOGO
        </span>

        <span class="text-xl font-semibold tracking-wide text-[#F5F5F1]">
            DailyExpense
        </span>
    </div>

        <nav class="flex-1 py-4">
            @php
                $currentRoute = request()->route()->getName();
            @endphp
            <a href="{{ route('statistik.index') }}"
               class="block px-6 py-4 text-[#F5F5F1] {{ $currentRoute == 'statistik.index' ? 'bg-white/20' : 'hover:bg-white/10' }} {{ $currentRoute == 'statistik.index' ? 'border-l-4 border-white' : '' }}">
                Statistik
            </a>
            <a href="{{ route('pengeluaran.index') }}"
               class="block px-6 py-4 text-[#F5F5F1] {{ str_contains($currentRoute, 'pengeluaran') ? 'bg-white/20' : 'hover:bg-white/10' }} {{ str_contains($currentRoute, 'pengeluaran') ? 'border-l-4 border-white' : '' }}">
                Pengeluaran
            </a>
            <a href="{{ route('kategori.index') }}"
               class="block px-6 py-4 text-[#F5F5F1] {{ str_contains($currentRoute, 'kategori') ? 'bg-white/20' : 'hover:bg-white/10' }} {{ str_contains($currentRoute, 'kategori') ? 'border-l-4 border-white' : '' }}">
                Kategori
            </a>
        </nav>
    </aside>

    <!-- MAIN -->
    <div class="flex-1 ml-64 min-h-screen flex flex-col">
        <!-- TOPBAR -->
        <header class="h-20 flex items-center justify-end px-10 bg-[#F5F5F1]">
            <div class="text-2xl font-bold text-[#388087]">DailyExpense</div>
        </header>

        <!-- CONTENT -->
        <main class="flex-1 px-8 py-0">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @yield('content')
        </main>

        <!-- FOOTER -->
        <footer class="bg-[#C2EDCE] py-4 text-center text-gray-700">
            <p>&copy; {{ date('Y') }} DailyExpense | 5230311065 Gagah Fajar</p>
        </footer>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
