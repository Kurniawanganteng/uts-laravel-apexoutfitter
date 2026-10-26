@extends('layouts.customer')

@section('content')
<div class="space-y-6">
    
    <!-- Title & Print Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">Laporan & Analisis Belanja Pelanggan</h1>
            <p class="text-xs md:text-sm text-slate-500 mt-1">Laporan rekapitulasi pengeluaran dan riwayat barang yang telah Anda beli.</p>
        </div>

        <button type="button" onclick="window.print()" class="no-print inline-flex items-center space-x-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md transition self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            <span>Cetak Laporan Belanja</span>
        </button>
    </div>

    <!-- Date Range Filter (No Print) -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 no-print">
        <form method="GET" action="{{ route('customer.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
            <div class="sm:col-span-5">
                <label class="block text-xs font-bold text-slate-700 mb-1">Mulai Tanggal:</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 focus:border-emerald-500 text-xs outline-none">
            </div>
            <div class="sm:col-span-5">
                <label class="block text-xs font-bold text-slate-700 mb-1">Sampai Tanggal:</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 focus:border-emerald-500 text-xs outline-none">
            </div>
            <div class="sm:col-span-2 flex space-x-2">
                <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-xl text-xs transition">
                    Filter
                </button>
                <a href="{{ route('customer.reports.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2 px-3 rounded-xl text-xs transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Statistics Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-1">
            <span class="text-xs font-semibold text-slate-400">Total Pengeluaran Selesai</span>
            <div class="text-2xl font-black text-emerald-700">Rp {{ number_format($totalSpent, 0, ',', '.') }}</div>
            <span class="text-[11px] text-slate-400">Dari seluruh pesanan lunas</span>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-1">
            <span class="text-xs font-semibold text-slate-400">Total Transaksi Sukses</span>
            <div class="text-2xl font-black text-slate-900">{{ $totalOrders }} Pesanan</div>
            <span class="text-[11px] text-emerald-600 font-semibold">Berhasil terverifikasi</span>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-1">
            <span class="text-xs font-semibold text-slate-400">Rata-rata Nilai Belanja</span>
            <div class="text-2xl font-black text-blue-700">Rp {{ number_format($averageSpent, 0, ',', '.') }}</div>
            <span class="text-[11px] text-slate-400">Per transaksi checkout</span>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-1">
            <span class="text-xs font-semibold text-slate-400">Total Unit Barang</span>
            <div class="text-2xl font-black text-purple-700">{{ $totalItemsPurchased }} Item</div>
            <span class="text-[11px] text-slate-400">Barang diterima pelanggan</span>
        </div>

    </div>

    <!-- Top Products Purchased Breakdown -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
        <h2 class="text-base font-bold text-slate-900">Rincian Barang yang Dibeli</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                        <th class="p-3.5">Nama Produk</th>
                        <th class="p-3.5 text-center">Jumlah Unit</th>
                        <th class="p-3.5 text-right">Total Akumulasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($purchasedProducts as $product)
                    <tr>
                        <td class="p-3.5 font-bold text-slate-800 text-sm">{{ $product['name'] }}</td>
                        <td class="p-3.5 text-center font-bold text-slate-700">{{ $product['quantity'] }} unit</td>
                        <td class="p-3.5 text-right font-mono font-bold text-emerald-700">Rp {{ number_format($product['total'], 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-6 text-center text-slate-400">Belum ada riwayat pembelian produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Orders Log Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
        <h2 class="text-base font-bold text-slate-900">Daftar Transaksi Pembayaran</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                        <th class="p-3.5">Nomor Transaksi</th>
                        <th class="p-3.5">Tanggal</th>
                        <th class="p-3.5">Penerima</th>
                        <th class="p-3.5 text-right">Total</th>
                        <th class="p-3.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                    <tr>
                        <td class="p-3.5 font-mono font-bold text-slate-900">
                            <a href="{{ route('customer.orders.show', $order->id) }}" class="text-emerald-700 hover:underline">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td class="p-3.5 text-slate-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-3.5 text-slate-700">{{ $order->customer_name }}</td>
                        <td class="p-3.5 text-right font-mono font-bold text-emerald-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="p-3.5 text-center">{!! $order->status_badge !!}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-slate-400">Tidak ada data transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
