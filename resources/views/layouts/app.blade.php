<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Apex Outfitter') }} - Sistem Manajemen Inventaris & POS Outdoor</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Top Navigation Bar -->
    <nav class="bg-slate-900 text-white shadow-lg sticky top-0 z-50 no-print border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand & Main Links -->
                <div class="flex items-center space-x-6">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 group-hover:scale-105 transition-transform">
                            <x-application-logo class="w-10 h-10 shadow-md shadow-emerald-900/40 rounded-2xl" />
                        </div>
                        <div>
                            <span class="font-black text-xl tracking-tight bg-gradient-to-r from-white via-slate-100 to-emerald-300 bg-clip-text text-transparent">Apex <span class="text-emerald-400">Outfitter</span></span>
                            <span class="hidden sm:inline-block text-[10px] font-bold tracking-wider text-emerald-400 bg-emerald-950/90 px-2 py-0.5 rounded-full ml-1 border border-emerald-800">OUTDOOR GEAR & POS</span>
                        </div>
                    </a>

                    <!-- Navigation Links -->
                    <div class="hidden lg:flex items-center space-x-1 text-sm font-medium">
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-emerald-700 text-white shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('products.index') }}" class="px-3 py-2 rounded-lg transition {{ request()->routeIs('products.*') ? 'bg-emerald-700 text-white shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Katalog Alat
                        </a>
                        <a href="{{ route('sales.index') }}" class="px-3 py-2 rounded-lg transition {{ request()->routeIs('sales.*') ? 'bg-emerald-700 text-white shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Kasir POS
                        </a>
                        <a href="{{ route('purchases.index') }}" class="px-3 py-2 rounded-lg transition {{ request()->routeIs('purchases.*') ? 'bg-emerald-700 text-white shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Pasokan Stok
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="px-3 py-2 rounded-lg transition relative {{ request()->routeIs('admin.orders.*') ? 'bg-emerald-700 text-white shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Pesanan Online
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.reports.*') ? 'bg-emerald-700 text-white shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Laporan Bisnis
                        </a>

                        @can('admin-only')
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.outside="open = false" class="px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 flex items-center space-x-1 transition">
                                <span>Master Data</span>
                                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="open" x-cloak class="absolute left-0 mt-2 w-48 bg-slate-800 border border-slate-700 rounded-xl shadow-xl py-2 z-50">
                                <a href="{{ route('categories.index') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-slate-700 hover:text-white">Kategori Outdoor</a>
                                <a href="{{ route('suppliers.index') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-slate-700 hover:text-white">Supplier Pemasok</a>
                                <a href="{{ route('users.index') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-slate-700 hover:text-white">Kelola Pengguna</a>
                            </div>
                        </div>
                        @endcan
                    </div>
                </div>

                <!-- Right Side Controls -->
                <div class="flex items-center space-x-3">
                    <!-- Switch to Storefront Button -->
                    <a href="{{ route('shop.index') }}" target="_blank" class="hidden sm:inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-800 text-emerald-400 hover:bg-slate-700 border border-slate-700 transition">
                        <span>Portal Toko Online</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    </a>

                    <!-- POS Quick Shortcut Button -->
                    <a href="{{ route('sales.create') }}" class="inline-flex items-center space-x-1.5 bg-gradient-to-r from-emerald-700 to-teal-700 hover:from-emerald-600 hover:to-teal-600 text-white px-3.5 py-1.5 rounded-lg text-xs font-bold shadow-md shadow-emerald-900/30 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        <span>Kasir Baru</span>
                    </a>

                    <!-- User Profile & Logout -->
                    <div class="flex items-center space-x-2 pl-3 border-l border-slate-800">
                        <div class="text-right hidden md:block">
                            <div class="text-xs font-bold text-white">{{ auth()->user()->name ?? 'Pengguna' }}</div>
                            <span class="inline-block text-[10px] font-semibold uppercase px-2 py-0.5 rounded-full {{ auth()->user()->isAdmin() ? 'bg-amber-900/60 text-amber-300 border border-amber-700' : 'bg-blue-900/60 text-blue-300 border border-blue-700' }}">
                                {{ auth()->user()->isAdmin() ? 'OWNER / ADMIN' : 'STAFF KASIR & GUDANG' }}
                            </span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" title="Keluar dari sistem" class="p-2 text-slate-400 hover:text-red-400 hover:bg-slate-800 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Flash Alert Messages: Success -->
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-300 p-4 shadow-sm flex items-start justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-emerald-950">Operasi Berhasil</h4>
                    <p class="text-xs text-emerald-800 mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </div>
        @endif

        <!-- Flash Alert Messages: Error -->
        @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 rounded-2xl bg-red-50 border border-red-300 p-4 shadow-sm flex items-start justify-between">
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-700 flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-red-950">Pemberitahuan Sistem</h4>
                    <p class="text-xs text-red-800 mt-0.5">{{ session('error') }}</p>
                </div>
            </div>
            <button @click="show = false" class="text-red-500 hover:text-red-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </div>
        @endif

        @if($errors->any())
        <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 rounded-2xl bg-red-50 border border-red-300 p-4 shadow-sm flex items-start justify-between">
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-700 flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-red-950">Validasi Data Gagal</h4>
                    <ul class="text-xs text-red-800 list-disc list-inside mt-1 space-y-0.5">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button @click="show = false" class="text-red-500 hover:text-red-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </div>
        @endif

        <!-- Dynamic Content Slot -->
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 mt-auto text-xs text-slate-500 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <span class="font-bold text-slate-800">Apex Outfitter POS & Inventory System</span> &copy; {{ date('Y') }}. Sistem Toko Perlengkapan Outdoor.
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-slate-400">Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</span>
                <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-semibold">MariaDB Connected</span>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
