@extends('layouts.customer')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">Checkout & Pembayaran</h1>
        <p class="text-xs md:text-sm text-slate-500 mt-1">Lengkapi data pengiriman dan pilih metode pembayaran digital untuk menyelesaikan pesanan perlengkapan outdoor Anda.</p>
    </div>

    <form action="{{ route('customer.checkout.process') }}" method="POST" x-data="{ method: 'qris', channel: 'qris' }" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        @csrf

        <!-- Left Column: Form Details & Payment Selector -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Shipping Information Card -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
                <div class="flex items-center space-x-2 border-b border-slate-100 pb-3">
                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-bold">1</span>
                    <h2 class="text-base font-bold text-slate-900">Informasi Penerima & Alamat Ekspedisi</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Penerima <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', $user->name ?? 'Jack Kurniawan') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-200 text-sm outline-none transition" required placeholder="Jack Kurniawan">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp / Telepon <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone', '081234567890') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-200 text-sm outline-none transition" required placeholder="081234567890">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Lengkap Pengiriman <span class="text-red-500">*</span></label>
                    <textarea name="shipping_address" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-200 text-sm outline-none transition" required placeholder="Jalan, Nomor Rumah, RT/RW, Kelurahan, Kecamatan, Kota & Kode Pos">{{ old('shipping_address', 'Jl. Merdeka No. 10, Gambir, Jakarta Pusat') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Tambahan untuk Penjual (Opsional)</label>
                    <input type="text" name="notes" value="{{ old('notes') }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 focus:border-emerald-600 text-xs outline-none" placeholder="Contoh: Packing waterproof tebal untuk peralatan gunung">
                </div>
            </div>

            <!-- Payment Gateway Selector Card -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-5">
                <div class="flex items-center space-x-2 border-b border-slate-100 pb-3">
                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-bold">2</span>
                    <h2 class="text-base font-bold text-slate-900">Metode Pembayaran (ApexPay Gateway)</h2>
                </div>

                <!-- Method Options -->
                <div class="space-y-3">
                    
                    <!-- QRIS Option -->
                    <label class="flex items-start p-4 rounded-2xl border transition cursor-pointer" :class="method === 'qris' ? 'border-emerald-600 bg-emerald-50/50 ring-1 ring-emerald-600' : 'border-slate-200 hover:bg-slate-50'">
                        <input type="radio" name="payment_method" value="qris" x-model="method" @change="channel = 'qris'" class="mt-1 text-emerald-700 focus:ring-emerald-600">
                        <div class="ml-3.5 flex-1">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-sm text-slate-900">QRIS (Semua E-Wallet & Mobile Banking)</span>
                                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 border border-emerald-200">Scan & Pay</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">Scan instan menggunakan BCA Mobile, Livin' Mandiri, BRImo, BNI, GoPay, OVO, atau DANA.</p>
                        </div>
                    </label>

                    <!-- Virtual Account Option -->
                    <label class="flex items-start p-4 rounded-2xl border transition cursor-pointer" :class="method === 'bank_transfer' ? 'border-emerald-600 bg-emerald-50/50 ring-1 ring-emerald-600' : 'border-slate-200 hover:bg-slate-50'">
                        <input type="radio" name="payment_method" value="bank_transfer" x-model="method" @change="channel = 'bca'" class="mt-1 text-emerald-700 focus:ring-emerald-600">
                        <div class="ml-3.5 flex-1">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-sm text-slate-900">Virtual Account Bank (Konfirmasi Otomatis)</span>
                                <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-blue-100 text-blue-800 border border-blue-200">24 Jam</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">Transfer rekening virtual tanpa perlu kirim bukti transfer manual.</p>
                            
                            <!-- Sub-bank selectors if VA selected -->
                            <div x-show="method === 'bank_transfer'" x-cloak class="mt-3 grid grid-cols-2 sm:grid-cols-4 gap-2 pt-2 border-t border-slate-200">
                                <label class="p-2.5 rounded-xl border text-center cursor-pointer transition text-xs font-bold" :class="channel === 'bca' ? 'bg-emerald-700 text-white border-emerald-700' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'">
                                    <input type="radio" name="payment_channel" value="bca" x-model="channel" class="sr-only">
                                    BCA VA
                                </label>
                                <label class="p-2.5 rounded-xl border text-center cursor-pointer transition text-xs font-bold" :class="channel === 'mandiri' ? 'bg-emerald-700 text-white border-emerald-700' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'">
                                    <input type="radio" name="payment_channel" value="mandiri" x-model="channel" class="sr-only">
                                    Mandiri
                                </label>
                                <label class="p-2.5 rounded-xl border text-center cursor-pointer transition text-xs font-bold" :class="channel === 'bni' ? 'bg-emerald-700 text-white border-emerald-700' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'">
                                    <input type="radio" name="payment_channel" value="bni" x-model="channel" class="sr-only">
                                    BNI VA
                                </label>
                                <label class="p-2.5 rounded-xl border text-center cursor-pointer transition text-xs font-bold" :class="channel === 'bri' ? 'bg-emerald-700 text-white border-emerald-700' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'">
                                    <input type="radio" name="payment_channel" value="bri" x-model="channel" class="sr-only">
                                    BRI VA
                                </label>
                            </div>
                        </div>
                    </label>

                    <!-- E-Wallet Option -->
                    <label class="flex items-start p-4 rounded-2xl border transition cursor-pointer" :class="method === 'ewallet' ? 'border-emerald-600 bg-emerald-50/50 ring-1 ring-emerald-600' : 'border-slate-200 hover:bg-slate-50'">
                        <input type="radio" name="payment_method" value="ewallet" x-model="method" @change="channel = 'gopay'" class="mt-1 text-emerald-700 focus:ring-emerald-600">
                        <div class="ml-3.5 flex-1">
                            <span class="font-bold text-sm text-slate-900">E-Wallet Indonesia</span>
                            <p class="text-xs text-slate-500 mt-0.5">Selesaikan transaksi via aplikasi GoPay, OVO, atau DANA.</p>
                            
                            <div x-show="method === 'ewallet'" x-cloak class="mt-3 grid grid-cols-3 gap-2 pt-2 border-t border-slate-200">
                                <label class="p-2 rounded-xl border text-center cursor-pointer transition text-xs font-bold" :class="channel === 'gopay' ? 'bg-emerald-700 text-white border-emerald-700' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'">
                                    <input type="radio" name="payment_channel" value="gopay" x-model="channel" class="sr-only">
                                    GoPay
                                </label>
                                <label class="p-2 rounded-xl border text-center cursor-pointer transition text-xs font-bold" :class="channel === 'ovo' ? 'bg-emerald-700 text-white border-emerald-700' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'">
                                    <input type="radio" name="payment_channel" value="ovo" x-model="channel" class="sr-only">
                                    OVO
                                </label>
                                <label class="p-2 rounded-xl border text-center cursor-pointer transition text-xs font-bold" :class="channel === 'dana' ? 'bg-emerald-700 text-white border-emerald-700' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'">
                                    <input type="radio" name="payment_channel" value="dana" x-model="channel" class="sr-only">
                                    DANA
                                </label>
                            </div>
                        </div>
                    </label>

                </div>
            </div>
        </div>

        <!-- Right Column: Order Summary & Confirm -->
        <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-6">
            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Ringkasan Pesanan Alat</h2>

            <!-- Items Brief -->
            <div class="space-y-3 max-h-64 overflow-y-auto pr-1">
                @foreach($cartItems as $item)
                @php $product = $item['product']; @endphp
                <div class="flex items-center justify-between text-xs py-1.5 border-b border-slate-50">
                    <div class="flex-1 pr-2">
                        <div class="font-bold text-slate-800 truncate">{{ $product->name }}</div>
                        <div class="text-slate-400 font-mono">{{ $item['quantity'] }} unit @ Rp {{ number_format($item['unit_price'], 0, ',', '.') }}</div>
                    </div>
                    <div class="font-bold text-slate-900 font-mono">
                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Total Cost Summary -->
            <div class="space-y-2.5 text-xs text-slate-600 pt-2 border-t border-slate-100">
                <div class="flex justify-between">
                    <span>Subtotal Produk:</span>
                    <strong class="text-slate-800 font-mono">Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong>
                </div>
                <div class="flex justify-between">
                    <span>Biaya Pengiriman Ekspedisi:</span>
                    <strong class="text-emerald-800">GRATIS</strong>
                </div>
                <div class="flex justify-between">
                    <span>Biaya Layanan Gateway:</span>
                    <strong class="text-emerald-800 font-mono">Rp 0</strong>
                </div>
                <div class="border-t border-slate-200 pt-3 flex justify-between items-baseline">
                    <span class="font-bold text-slate-900 text-sm">Total Pembayaran:</span>
                    <span class="text-2xl font-black text-emerald-800 font-mono">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="space-y-3 pt-2">
                <button type="submit" class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold py-3.5 px-6 rounded-2xl shadow-lg shadow-emerald-900/30 transition text-sm flex items-center justify-center space-x-2">
                    <span>Lanjut ke Pembayaran Gateway</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </button>
                <a href="{{ route('customer.cart.index') }}" class="block text-center text-xs font-semibold text-slate-500 hover:text-slate-700">
                    &larr; Kembali ke Keranjang Belanja
                </a>
            </div>
        </div>

    </form>
</div>
@endsection
