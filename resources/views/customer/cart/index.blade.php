@extends('layouts.customer')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">Keranjang Belanja Pelanggan</h1>
            <p class="text-xs md:text-sm text-slate-500 mt-1">Periksa item belanja Anda sebelum melanjutkan ke proses pembayaran digital.</p>
        </div>
        <a href="{{ route('shop.index') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 px-3.5 py-2 rounded-xl border border-emerald-200 transition">
            + Tambah Produk Lain
        </a>
    </div>

    @if(!empty($cartItems))
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Cart Items Table List -->
        <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-slate-100 flex items-center justify-between">
                <span class="text-sm font-bold text-slate-700">Daftar Produk ({{ $totalItems }} unit)</span>
                <form action="{{ route('customer.cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan seluruh keranjang belanja Anda?')">
                    @csrf
                    <button type="submit" class="text-xs text-red-600 hover:text-red-700 font-semibold">
                        Kosongkan Keranjang
                    </button>
                </form>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach($cartItems as $productId => $item)
                @php $product = $item['product']; @endphp
                <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    
                    <!-- Product Info -->
                    <div class="flex items-start space-x-4">
                        <div class="w-16 h-16 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-emerald-600 flex-shrink-0">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-mono font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded">SKU: {{ $product->sku }}</span>
                            <h3 class="font-bold text-sm text-slate-900 leading-snug">
                                <a href="{{ route('shop.show', $product->id) }}" class="hover:text-emerald-700">{{ $product->name }}</a>
                            </h3>
                            <div class="text-xs text-slate-500">
                                Harga Satuan: <strong class="text-slate-800">Rp {{ number_format($item['unit_price'], 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity Controls & Subtotal -->
                    <div class="flex items-center justify-between sm:justify-end sm:space-x-6 pt-2 sm:pt-0 border-t sm:border-0 border-slate-100">
                        <!-- Qty Form -->
                        <form action="{{ route('customer.cart.update', $product->id) }}" method="POST" class="flex items-center space-x-1.5">
                            @csrf
                            @method('PUT')
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['max_stock'] }}" class="w-16 px-2 py-1 text-center font-bold text-sm rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-200 outline-none">
                            <button type="submit" title="Perbarui jumlah" class="p-1.5 text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                            </button>
                        </form>

                        <!-- Subtotal -->
                        <div class="text-right">
                            <span class="block text-[10px] text-slate-400 font-semibold">Subtotal</span>
                            <span class="text-sm font-extrabold text-emerald-700">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                        </div>

                        <!-- Remove Button -->
                        <form action="{{ route('customer.cart.remove', $product->id) }}" method="POST" onsubmit="return confirm('Hapus item ini dari keranjang?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Hapus produk" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </form>
                    </div>

                </div>
                @endforeach
            </div>
        </div>

        <!-- Order Summary & Checkout Card -->
        <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-6">
            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Ringkasan Belanja</h2>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between text-slate-600">
                    <span>Total Item Dipilih:</span>
                    <strong class="text-slate-800">{{ $totalItems }} unit</strong>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Sumber Harga:</span>
                    <span class="text-emerald-700 font-semibold text-xs bg-emerald-50 px-2 py-0.5 rounded">Database Server</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Biaya Layanan Gateway:</span>
                    <strong class="text-emerald-700">Gratis (Rp 0)</strong>
                </div>
                <div class="border-t border-slate-100 pt-3 flex justify-between items-baseline">
                    <span class="font-bold text-slate-900 text-base">Total Tagihan:</span>
                    <span class="text-2xl font-extrabold text-emerald-700">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Checkout Action -->
            <div class="space-y-2 pt-2">
                <a href="{{ route('customer.checkout.index') }}" class="w-full flex items-center justify-center space-x-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3.5 px-6 rounded-2xl shadow-lg shadow-emerald-600/30 transition text-sm">
                    <span>Lanjut ke Checkout</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
                <p class="text-[11px] text-center text-slate-400 flex items-center justify-center space-x-1">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    <span>Transaksi diamankan dengan enkripsi standar industri & ApexPay Gateway.</span>
                </p>
            </div>
        </div>

    </div>
    @else
    <!-- Empty Cart Screen -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-12 text-center max-w-xl mx-auto space-y-4">
        <div class="w-20 h-20 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
        </div>
        <h2 class="text-xl font-bold text-slate-800">Keranjang Belanja Anda Kosong</h2>
        <p class="text-sm text-slate-500 leading-relaxed">
            Anda belum menambahkan produk apa pun ke keranjang belanja. Silakan jelajahi katalog produk kami untuk menemukan barang inventaris yang Anda butuhkan.
        </p>
        <div class="pt-2">
            <a href="{{ route('shop.index') }}" class="inline-flex items-center space-x-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-xl shadow-md shadow-emerald-600/20 transition text-sm">
                <span>Mulai Belanja Sekarang</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
