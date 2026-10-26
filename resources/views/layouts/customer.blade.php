<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Apex Outfitter') }} - Toko Perlengkapan Outdoor & Petualangan</title>
    
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
                        emerald: {
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

    <style>
        [x-cloak] { display: none !important; }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Announcement Bar -->
    <div class="bg-gradient-to-r from-slate-900 via-emerald-950 to-slate-900 text-slate-200 text-xs py-2 px-4 text-center font-medium no-print border-b border-emerald-900/50">
        <span>Toko Perlengkapan Outdoor & Ekspedisi Gunung — Menyediakan Peralatan Original dengan Integrasi Pembayaran QRIS & Virtual Account Bank.</span>
    </div>

    <!-- Customer Navigation Bar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-18 py-3">
                
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-6">
                    <a href="{{ route('shop.index') }}" class="flex items-center space-x-3 group">
                        <div class="w-11 h-11 group-hover:scale-105 transition-transform">
                            <x-application-logo class="w-11 h-11 rounded-2xl shadow-md shadow-emerald-900/30" />
                        </div>
                        <div>
                            <span class="font-black text-2xl tracking-tight text-slate-900">Apex <span class="text-emerald-700">Outfitter</span></span>
                            <span class="block text-[11px] text-slate-400 font-semibold tracking-wide">Outdoor Gear & Expedition Equipment</span>
                        </div>
                    </a>

                    <!-- Nav Links -->
                    <nav class="hidden md:flex items-center space-x-2 text-sm font-semibold">
                        <a href="{{ route('shop.index') }}" class="px-3 py-2 rounded-xl transition {{ request()->routeIs('shop.*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:text-emerald-800 hover:bg-slate-50' }}">
                            Katalog Alat
                        </a>
                        @auth
                        <a href="{{ route('customer.orders.index') }}" class="px-3 py-2 rounded-xl transition {{ request()->routeIs('customer.orders.*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:text-emerald-800 hover:bg-slate-50' }}">
                            Riwayat Pesanan
                        </a>
                        <a href="{{ route('customer.reports.index') }}" class="px-3 py-2 rounded-xl transition {{ request()->routeIs('customer.reports.*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:text-emerald-800 hover:bg-slate-50' }}">
                            Laporan Belanja
                        </a>
                        @endauth
                    </nav>
                </div>

                <!-- Right Side: Cart & Account -->
                <div class="flex items-center space-x-4">
                    @php
                        $cartCount = 0;
                        if(session()->has('cart')) {
                            foreach(session('cart') as $c) {
                                $cartCount += ($c['quantity'] ?? 1);
                            }
                        }
                    @endphp

                    <!-- Cart Pill -->
                    <a href="{{ route('customer.cart.index') }}" class="relative inline-flex items-center space-x-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-emerald-50 text-slate-700 hover:text-emerald-800 transition font-medium text-sm">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="hidden sm:inline">Keranjang</span>
                        <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-emerald-700 rounded-full">
                            {{ $cartCount }}
                        </span>
                    </a>

                    <!-- User Account / Auth Buttons -->
                    @auth
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.outside="open = false" class="flex items-center space-x-2.5 p-1.5 rounded-xl border border-slate-200 hover:border-slate-300 transition">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-900 font-bold flex items-center justify-center text-xs">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <div class="text-left hidden lg:block pr-1">
                                <div class="text-xs font-bold text-slate-800 leading-tight">{{ auth()->user()->name }}</div>
                                <div class="text-[10px] text-emerald-700 font-semibold capitalize">{{ auth()->user()->role }}</div>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>

                        <div x-show="open" x-cloak class="absolute right-0 mt-2 w-56 bg-white border border-slate-200 rounded-2xl shadow-xl py-2 z-50">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs text-slate-400">Akun Terdaftar:</p>
                                <p class="text-sm font-bold text-slate-800 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-sm font-semibold text-emerald-700 hover:bg-emerald-50">
                                Dashboard Inventaris
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            @endif
                            <a href="{{ route('customer.orders.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                Riwayat Transaksi Saya
                            </a>
                            <a href="{{ route('customer.reports.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                Laporan Belanja Saya
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                                    Keluar (Logout)
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-emerald-800 px-3 py-2 rounded-xl transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="text-sm font-bold text-white bg-emerald-700 hover:bg-emerald-800 px-4 py-2 rounded-xl shadow-md shadow-emerald-800/20 transition">
                            Daftar Pelanggan
                        </a>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Slot -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Flash Alert Messages: Success -->
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-300 p-4 shadow-sm flex items-start justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-emerald-950">Transaksi Berhasil</h4>
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
                    <h4 class="text-sm font-bold text-red-950">Perhatian Pengisian Data</h4>
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

        @yield('content')
    </main>

    <!-- Store Features & Guarantees -->
    <section class="bg-white border-t border-slate-200 py-10 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center sm:text-left">
                <div class="flex items-center space-x-4 bg-slate-50 p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-900">Perlengkapan Teruji</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Seluruh unit alat standar pendakian dan ekspedisi gunung.</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4 bg-slate-50 p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-900">Pembayaran Digital QRIS</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Integrasi QRIS Nasional, Virtual Account Bank & E-Wallet.</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4 bg-slate-50 p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-900">Stok Realtime Terkunci</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Pencegahan oversell dengan database row locking.</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4 bg-slate-50 p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-900">Faktur & Struk Digital</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Bukti transaksi tersimpan rapi dan dapat dicetak.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-10 no-print text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between border-b border-slate-800 pb-6 gap-4">
                <div class="flex items-center space-x-3">
                    <x-application-logo class="w-8 h-8 rounded-lg" />
                    <span class="text-white font-black text-lg tracking-tight">Apex <span class="text-emerald-400">Outfitter</span></span>
                </div>
                <div class="text-center sm:text-right">
                    <p class="text-slate-300 font-medium">Sistem Informasi Manajemen Inventaris & Portal Pelanggan Outdoor</p>
                    <p class="text-slate-500 mt-1">Dibuat dengan Laravel 12 & MariaDB Database.</p>
                </div>
            </div>
            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p>&copy; {{ date('Y') }} Apex Outfitter. Hak Cipta Dilindungi.</p>
                <div class="flex space-x-4 text-slate-500">
                    <a href="{{ route('shop.index') }}" class="hover:text-slate-300">Katalog Alat</a>
                    <a href="{{ route('login') }}" class="hover:text-slate-300">Login Karyawan / Backoffice</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
