@extends('layouts.customer')

@section('content')
<div class="space-y-8">
    <!-- Breadcrumb -->
    <nav class="flex text-xs font-semibold text-slate-500 space-x-2">
        <a href="{{ route('shop.index') }}" class="hover:text-emerald-700">Katalog</a>
        <span>/</span>
        <span class="text-slate-700">{{ $product->category->name }}</span>
        <span>/</span>
        <span class="text-slate-900 truncate">{{ $product->name }}</span>
    </nav>

    <!-- Product Detail Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 md:p-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-start">
            
            <!-- Product Visual Presentation -->
            <div class="space-y-4">
                <div class="h-80 md:h-96 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center relative overflow-hidden">
                    <span class="absolute top-4 left-4 z-10 text-xs font-bold px-3 py-1 rounded-full bg-white/90 backdrop-blur text-slate-800 shadow-sm border border-slate-200">
                        {{ $product->category->name }}
                    </span>
                    @if($product->image)
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-32 h-32 rounded-3xl bg-white shadow-xl flex items-center justify-center text-slate-400">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    @endif
                </div>
                <div class="grid grid-cols-2 gap-3 text-xs text-slate-500 font-medium">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-center">
                        <span class="block text-slate-400 font-normal">Kondisi Alat</span>
                        <strong class="text-slate-800 text-sm">100% Original Brand</strong>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-center">
                        <span class="block text-slate-400 font-normal">Garansi Toko</span>
                        <strong class="text-slate-800 text-sm">Resmi Distributor</strong>
                    </div>
                </div>
            </div>

            <!-- Product Specs & Buying Options -->
            <div class="space-y-6">
                <div>
                    <span class="text-xs font-mono font-bold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                        SKU: {{ $product->sku }}
                    </span>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mt-2 leading-tight">
                        {{ $product->name }}
                    </h1>
                </div>

                <!-- Price & Stock Status -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                    <div class="text-xs font-semibold text-slate-400">Harga Resmi (Server DB):</div>
                    <div class="text-3xl font-extrabold text-emerald-800 font-mono">
                        Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                    </div>
                    <div class="pt-2 flex items-center space-x-2 text-xs">
                        <span class="font-semibold text-slate-600">Status Ketersediaan:</span>
                        <span class="font-bold px-2.5 py-0.5 rounded-full {{ $product->stock <= 0 ? 'bg-red-100 text-red-800' : ($product->stock <= $product->minimum_stock ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800') }}">
                            {{ $product->stock > 0 ? "Tersedia {$product->stock} unit di gudang" : 'Stok Habis' }}
                        </span>
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <h3 class="text-sm font-bold text-slate-800">Spesifikasi & Deskripsi Peralatan</h3>
                    <div class="text-sm text-slate-600 leading-relaxed space-y-2">
                        <p>{{ $product->description ?? 'Peralatan pendakian dan ekspedisi outdoor dengan standar ketahanan tinggi yang telah diinspeksi kualitasnya.' }}</p>
                    </div>
                </div>

                <!-- Buy Action Form -->
                @if($product->stock > 0)
                <form action="{{ route('customer.cart.add', $product->id) }}" method="POST" class="pt-4 border-t border-slate-200 space-y-4">
                    @csrf
                    <div class="flex items-center space-x-4">
                        <label class="text-xs font-bold text-slate-700">Jumlah Unit:</label>
                        <div class="flex items-center border border-slate-300 rounded-xl bg-white overflow-hidden shadow-sm">
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-20 text-center py-2 text-sm font-bold outline-none">
                        </div>
                        <span class="text-xs text-slate-400">Maksimal: {{ $product->stock }} unit</span>
                    </div>

                    <div class="flex space-x-3 pt-2">
                        <button type="submit" class="flex-1 bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold py-3.5 px-6 rounded-2xl shadow-lg shadow-emerald-900/30 transition flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            <span>Masukkan ke Keranjang</span>
                        </button>
                    </div>
                </form>
                @else
                <div class="p-4 bg-red-50 border border-red-200 rounded-2xl text-red-700 text-sm font-medium">
                    Mohon maaf, saat ini stok produk sedang kosong. Silakan periksa kembali nanti saat pasokan baru telah tiba dari supplier.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
