@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ tab: 'pos' }">
    
    <!-- Title & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">Pusat Laporan & Analitik Bisnis</h1>
            <p class="text-xs md:text-sm text-slate-500 mt-1">Laporan terpadu penjualan kasir POS, pesanan online, pembelian stok, dan estimasi laba.</p>
        </div>

        <a href="{{ route('admin.reports.index', array_merge(request()->all(), ['print' => 1])) }}" target="_blank" class="inline-flex items-center space-x-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md transition self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            <span>Buka Versi Cetak / PDF</span>
        </a>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
            <div class="sm:col-span-5">
                <label class="block text-xs font-bold text-slate-700 mb-1">Periode Mulai:</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 focus:border-emerald-500 text-xs outline-none">
            </div>
            <div class="sm:col-span-5">
                <label class="block text-xs font-bold text-slate-700 mb-1">Periode Sampai:</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 focus:border-emerald-500 text-xs outline-none">
            </div>
            <div class="sm:col-span-2 flex space-x-2">
                <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-xl text-xs transition">
                    Terapkan
                </button>
                <a href="{{ route('admin.reports.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2 px-3 rounded-xl text-xs transition text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Executive Financial Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Omset Keseluruhan -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-400">
                <span>Grand Total Omset Penjualan</span>
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            </div>
            <div class="text-2xl font-black text-emerald-700">
                Rp {{ number_format($grandTotalRevenue, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-slate-500 flex justify-between pt-1 border-t border-slate-100">
                <span>POS: Rp {{ number_format($totalPosRevenue, 0, ',', '.') }}</span>
                <span>Online: Rp {{ number_format($totalOnlineRevenue, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Total Pembelian Stok (Biaya Modal) -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-400">
                <span>Total Pengeluaran Stok</span>
                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
            </div>
            <div class="text-2xl font-black text-blue-700">
                Rp {{ number_format($totalPurchaseCost, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-slate-500 pt-1 border-t border-slate-100">
                Dari {{ $purchases->count() }} transaksi pembelian supplier
            </div>
        </div>

        <!-- Estimasi Laba Kotor -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-400">
                <span>Estimasi Laba Kotor</span>
                <span class="w-2 h-2 rounded-full {{ $estimatedProfit >= 0 ? 'bg-purple-500' : 'bg-red-500' }}"></span>
            </div>
            <div class="text-2xl font-black {{ $estimatedProfit >= 0 ? 'text-purple-700' : 'text-red-600' }}">
                Rp {{ number_format($estimatedProfit, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-slate-500 pt-1 border-t border-slate-100">
                Omset Total dikurangi Pengeluaran Stok
            </div>
        </div>

        <!-- Total Nilai Aset Gudang -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-400">
                <span>Nilai Aset Stok Gudang</span>
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
            </div>
            <div class="text-2xl font-black text-amber-700">
                Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-slate-500 pt-1 border-t border-slate-100">
                Peringatan Stok Kritis: <strong class="text-red-600">{{ $lowStockCount }} produk</strong>
            </div>
        </div>

    </div>

    <!-- Navigation Tabs for Detailed Reports -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <!-- Tab Headers -->
        <div class="flex border-b border-slate-200 bg-slate-50 text-xs font-bold overflow-x-auto">
            <button @click="tab = 'pos'" class="px-5 py-3.5 transition flex items-center space-x-2 border-b-2" :class="tab === 'pos' ? 'border-emerald-600 text-emerald-800 bg-white' : 'border-transparent text-slate-500 hover:text-slate-800'">
                <span>1. Penjualan POS Kasir ({{ $posSales->count() }})</span>
            </button>
            <button @click="tab = 'online'" class="px-5 py-3.5 transition flex items-center space-x-2 border-b-2" :class="tab === 'online' ? 'border-emerald-600 text-emerald-800 bg-white' : 'border-transparent text-slate-500 hover:text-slate-800'">
                <span>2. Pesanan Online Customer ({{ $onlineOrders->count() }})</span>
            </button>
            <button @click="tab = 'purchases'" class="px-5 py-3.5 transition flex items-center space-x-2 border-b-2" :class="tab === 'purchases' ? 'border-emerald-600 text-emerald-800 bg-white' : 'border-transparent text-slate-500 hover:text-slate-800'">
                <span>3. Pembelian Stok Masuk ({{ $purchases->count() }})</span>
            </button>
            <button @click="tab = 'stock'" class="px-5 py-3.5 transition flex items-center space-x-2 border-b-2" :class="tab === 'stock' ? 'border-emerald-600 text-emerald-800 bg-white' : 'border-transparent text-slate-500 hover:text-slate-800'">
                <span>4. Analisis Stok & Margin Produk</span>
            </button>
        </div>

        <!-- TAB 1: POS SALES -->
        <div x-show="tab === 'pos'" class="p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-sm text-slate-800">Daftar Transaksi Penjualan Kasir (POS)</h3>
                <span class="text-xs font-bold text-emerald-700">Subtotal: Rp {{ number_format($totalPosRevenue, 0, ',', '.') }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                            <th class="p-3">No. Transaksi</th>
                            <th class="p-3">Kasir Petugas</th>
                            <th class="p-3">Item Dijual</th>
                            <th class="p-3 text-right">Total Transaksi</th>
                            <th class="p-3">Waktu Transaksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($posSales as $sale)
                        <tr>
                            <td class="p-3 font-mono font-bold text-slate-900">{{ $sale->sale_number }}</td>
                            <td class="p-3 font-semibold text-slate-700">{{ $sale->user->name }}</td>
                            <td class="p-3 text-slate-500">
                                @foreach($sale->items as $it)
                                <span class="inline-block bg-slate-100 px-1.5 py-0.5 rounded text-[11px] mr-1 mb-1">
                                    {{ $it->product->name ?? 'Produk' }} ({{ $it->quantity }}x)
                                </span>
                                @endforeach
                            </td>
                            <td class="p-3 text-right font-mono font-bold text-emerald-700">
                                Rp {{ number_format($sale->total, 0, ',', '.') }}
                            </td>
                            <td class="p-3 text-slate-400">{{ $sale->sold_at }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="p-6 text-center text-slate-400">Tidak ada data penjualan kasir pada rentang tanggal ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 2: ONLINE ORDERS -->
        <div x-show="tab === 'online'" x-cloak class="p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-sm text-slate-800">Daftar Transaksi Pesanan Online Customer (Apex Outfitter)</h3>
                <span class="text-xs font-bold text-emerald-700">Subtotal: Rp {{ number_format($totalOnlineRevenue, 0, ',', '.') }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                            <th class="p-3">No. Pesanan</th>
                            <th class="p-3">Nama Pelanggan</th>
                            <th class="p-3">Metode Gateway</th>
                            <th class="p-3 text-right">Total Tagihan</th>
                            <th class="p-3 text-center">Status</th>
                            <th class="p-3">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($onlineOrders as $order)
                        <tr>
                            <td class="p-3 font-mono font-bold text-slate-900">{{ $order->order_number }}</td>
                            <td class="p-3 font-semibold text-slate-700">{{ $order->customer_name }}</td>
                            <td class="p-3">
                                <span class="uppercase font-bold text-[10px] bg-slate-100 text-slate-700 px-2 py-0.5 rounded">
                                    {{ $order->payment->payment_channel ?? 'ApexPay' }}
                                </span>
                            </td>
                            <td class="p-3 text-right font-mono font-bold text-emerald-700">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="p-3 text-center">{!! $order->status_badge !!}</td>
                            <td class="p-3 text-slate-400">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="p-6 text-center text-slate-400">Tidak ada transaksi online pada periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 3: PURCHASES -->
        <div x-show="tab === 'purchases'" x-cloak class="p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-sm text-slate-800">Daftar Pembelian Stok Masuk dari Supplier</h3>
                <span class="text-xs font-bold text-blue-700">Total Modal: Rp {{ number_format($totalPurchaseCost, 0, ',', '.') }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                            <th class="p-3">No. Pembelian</th>
                            <th class="p-3">Nama Supplier</th>
                            <th class="p-3">Petugas Input</th>
                            <th class="p-3 text-right">Total Pembelian</th>
                            <th class="p-3">Waktu Masuk</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($purchases as $purchase)
                        <tr>
                            <td class="p-3 font-mono font-bold text-slate-900">{{ $purchase->purchase_number }}</td>
                            <td class="p-3 font-semibold text-slate-800">{{ $purchase->supplier->name }}</td>
                            <td class="p-3 text-slate-600">{{ $purchase->user->name }}</td>
                            <td class="p-3 text-right font-mono font-bold text-blue-700">
                                Rp {{ number_format($purchase->total, 0, ',', '.') }}
                            </td>
                            <td class="p-3 text-slate-400">{{ $purchase->purchased_at }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="p-6 text-center text-slate-400">Tidak ada data pembelian stok pada rentang tanggal ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 4: STOCK & PRODUCT MARGIN -->
        <div x-show="tab === 'stock'" x-cloak class="p-6 space-y-4">
            <h3 class="font-bold text-sm text-slate-800">Analisis Ketersediaan Stok & Margin Keuntungan per Produk</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                            <th class="p-3">SKU</th>
                            <th class="p-3">Nama Produk</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3 text-right">Harga Beli</th>
                            <th class="p-3 text-right">Harga Jual</th>
                            <th class="p-3 text-right">Margin / Unit</th>
                            <th class="p-3 text-center">Sisa Stok</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($products as $prod)
                        @php $margin = $prod->selling_price - $prod->purchase_price; @endphp
                        <tr>
                            <td class="p-3 font-mono text-slate-500">{{ $prod->sku }}</td>
                            <td class="p-3 font-bold text-slate-800">{{ $prod->name }}</td>
                            <td class="p-3 text-slate-500">{{ $prod->category->name }}</td>
                            <td class="p-3 text-right font-mono text-slate-500">Rp {{ number_format($prod->purchase_price, 0, ',', '.') }}</td>
                            <td class="p-3 text-right font-mono font-semibold text-slate-800">Rp {{ number_format($prod->selling_price, 0, ',', '.') }}</td>
                            <td class="p-3 text-right font-mono font-bold text-emerald-700">+Rp {{ number_format($margin, 0, ',', '.') }}</td>
                            <td class="p-3 text-center">
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold {{ $prod->stock <= $prod->minimum_stock ? 'bg-red-100 text-red-800' : 'bg-emerald-100 text-emerald-800' }}">
                                    {{ $prod->stock }} Unit
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
