@extends('layouts.customer')

@section('content')
<div class="space-y-8">
    
    <!-- Hero Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-emerald-950 to-slate-900 text-white p-8 md:p-12 shadow-xl border border-slate-800">
        <div class="relative z-10 max-w-2xl space-y-4">
            <span class="inline-block text-xs font-bold tracking-wider uppercase px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                Official Outdoor Gear & Expedition Equipment
            </span>
            <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight text-white leading-tight">
                Perlengkapan Gunung, Camping & Petualangan Alam
            </h1>
            <p class="text-slate-300 text-sm md:text-base leading-relaxed">
                Jelajahi puncak tertinggi dengan peralatan standar ekspedisi: Tenda double layer, carrier ergonomis, sleeping bag bulu angsa, hingga perlengkapan survival teruji.
            </p>
            <div class="pt-2 flex flex-wrap items-center gap-3">
                <a href="#katalog" class="inline-flex items-center space-x-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-6 py-3 rounded-2xl shadow-lg shadow-emerald-900/30 transition text-sm">
                    <span>Lihat Seluruh Katalog</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
                </a>

                @auth
                    @if(auth()->user()->isCustomer())
                    <div class="inline-flex items-center space-x-2 bg-emerald-900/60 border border-emerald-500/40 rounded-2xl px-4 py-2.5 text-xs text-emerald-200">
                        <span>Masuk sebagai Pelanggan: <strong>{{ auth()->user()->name }}</strong></span>
                    </div>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center space-x-1.5 bg-slate-800/80 hover:bg-slate-800 text-white font-semibold px-4 py-3 rounded-2xl border border-slate-700 transition text-sm">
                        <span>Masuk Akun</span>
                    </a>
                @endauth
            </div>
        </div>
        <!-- Decorative Background Element -->
        <div class="absolute -right-10 -bottom-10 w-96 h-96 bg-emerald-600/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- Filter & Search Bar Section -->
    <div id="katalog" class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-4">
        <form method="GET" action="{{ route('shop.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
            
            <!-- Search Input -->
            <div class="md:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tenda, carrier, sepatu gunung, jaket..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-200 text-sm transition outline-none">
            </div>

            <!-- Category Filter -->
            <div class="md:col-span-4">
                <select name="category_id" class="w-full py-2.5 px-3 rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-200 text-sm transition outline-none bg-white">
                    <option value="">Semua Kategori Outdoor</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Sort By -->
            <div class="md:col-span-3 flex space-x-2">
                <select name="sort" class="w-full py-2.5 px-3 rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-200 text-sm transition outline-none bg-white">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Terendah</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama (A-Z)</option>
                </select>
                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition">
                    Cari
                </button>
            </div>
        </form>

        <!-- Category Pills -->
        <div class="flex items-center space-x-2 overflow-x-auto pb-1 pt-1 text-xs">
            <span class="text-slate-400 font-semibold uppercase tracking-wider whitespace-nowrap">Kategori:</span>
            <a href="{{ route('shop.index') }}" class="px-3 py-1.5 rounded-lg whitespace-nowrap transition {{ !request('category_id') ? 'bg-emerald-700 text-white font-bold' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                Semua
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('shop.index', ['category_id' => $cat->id]) }}" class="px-3 py-1.5 rounded-lg whitespace-nowrap transition {{ request('category_id') == $cat->id ? 'bg-emerald-700 text-white font-bold' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                {{ $cat->name }}
            </a>
            @endforeach
        </div>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden group">
            
            <!-- Product Header Image / Category Tag -->
            <div class="h-48 bg-slate-100 relative overflow-hidden flex items-center justify-center border-b border-slate-100">
                @if($product->image)
                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                <div class="w-16 h-16 rounded-2xl bg-slate-200 flex items-center justify-center text-slate-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
                @endif
                
                <span class="absolute top-3 left-3 text-[11px] font-bold px-2.5 py-1 rounded-full bg-white/90 backdrop-blur text-slate-800 shadow-sm border border-slate-200">
                    {{ $product->category->name }}
                </span>
                
                <!-- Stock Badge -->
                <span class="absolute top-3 right-3 text-[11px] font-bold px-2.5 py-1 rounded-full {{ $product->stock <= 0 ? 'bg-red-100 text-red-800 border border-red-200' : ($product->stock <= $product->minimum_stock ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-emerald-100 text-emerald-800 border border-emerald-200') }}">
                    @if($product->stock <= 0)
                        Habis
                    @elseif($product->stock <= $product->minimum_stock)
                        Sisa {{ $product->stock }} (Stok Menipis)
                    @else
                        Tersedia {{ $product->stock }}
                    @endif
                </span>
            </div>

            <!-- Product Body Details -->
            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                <div class="space-y-1.5">
                    <div class="text-[11px] font-mono font-semibold text-slate-400">SKU: {{ $product->sku }}</div>
                    <h3 class="font-bold text-slate-900 group-hover:text-emerald-700 transition line-clamp-2 leading-snug">
                        <a href="{{ route('shop.show', $product->id) }}">{{ $product->name }}</a>
                    </h3>
                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                        {{ $product->description ?? 'Perlengkapan outdoor bergaransi resmi dari distributor terpercaya.' }}
                    </p>
                </div>

                <!-- Price and Action -->
                <div class="pt-3 border-t border-slate-100 space-y-3">
                    <div>
                        <div class="text-[11px] text-slate-400 font-semibold">Harga Resmi Toko:</div>
                        <div class="text-xl font-extrabold text-emerald-800 font-mono">
                            Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                        </div>
                    </div>

                    @if($product->stock > 0)
                    <form action="{{ route('customer.cart.add', $product->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full flex items-center justify-center space-x-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold py-2.5 px-4 rounded-xl shadow-md shadow-emerald-900/20 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            <span class="text-xs">Tambah ke Keranjang</span>
                        </button>
                    </form>
                    @else
                    <button type="button" disabled class="w-full bg-slate-200 text-slate-400 font-semibold py-2.5 px-4 rounded-xl text-xs cursor-not-allowed">
                        Stok Produk Habis
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-200 p-8 space-y-3">
            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Tidak ada produk yang cocok</h3>
            <p class="text-sm text-slate-500 max-w-md mx-auto">Coba gunakan kata kunci pencarian yang lain atau ubah filter kategori produk.</p>
            <div class="pt-2">
                <a href="{{ route('shop.index') }}" class="inline-block bg-slate-900 text-white text-xs font-bold px-4 py-2 rounded-xl">Reset Pencarian</a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
