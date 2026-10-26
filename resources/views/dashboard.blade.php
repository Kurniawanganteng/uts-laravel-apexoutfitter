@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Welcome Header Card -->
    <div class="bg-gradient-to-r from-slate-900 via-emerald-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
        <div class="space-y-2 relative z-10">
            <div class="flex flex-wrap items-center gap-2">
                @if(auth()->user()->isAdmin())
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                    OWNER / ADMINISTRATOR — KONTROL PENUH
                </span>
                @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
                    STAFF OPERASIONAL — KASIR & GUDANG
                </span>
                @endif
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                    Database MariaDB Terhubung
                </span>
                <span class="text-xs text-slate-400 font-mono">{{ date('d F Y') }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                Selamat Datang, {{ auth()->user()->name }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 max-w-xl leading-relaxed">
                @if(auth()->user()->isAdmin())
                    Anda memiliki hak akses manajerial menyeluruh: memantau omset live, analisis laba finansial, hak eksklusif membatalkan transaksi, serta kelola akun dan supplier.
                @else
                    Fokus operasional harian: melayani transaksi kasir POS di toko fisik, input pasokan stok masuk dari supplier, dan pemrosesan pesanan online.
                @endif
            </p>
        </div>

        <div class="flex flex-wrap gap-2.5 relative z-10">
            <a href="{{ route('sales.create') }}" class="inline-flex items-center space-x-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-4 py-2.5 rounded-2xl shadow-lg shadow-emerald-900/40 transition text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                <span>Buka Kasir POS</span>
            </a>
            <a href="{{ route('purchases.create') }}" class="inline-flex items-center space-x-2 bg-slate-800 hover:bg-slate-700 text-white font-bold px-4 py-2.5 rounded-2xl border border-slate-700 transition text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                <span>+ Pasokan Masuk</span>
            </a>
        </div>
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- Quick Stats 4-Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Total Omset Bulan Ini -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Omset Bulan Ini</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-900 font-mono">
                Rp {{ number_format($grandTotalMonth, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-slate-500 flex justify-between pt-1 border-t border-slate-100">
                <span>Hari Ini: <strong class="text-emerald-800 font-mono">Rp {{ number_format($grandTotalToday, 0, ',', '.') }}</strong></span>
            </div>
        </div>

        <!-- Total Transaksi -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Transaksi</span>
                <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-900 font-mono">
                {{ $totalTransactions }}
            </div>
            <div class="text-[11px] text-slate-500 pt-1 border-t border-slate-100">
                Akumulasi POS Kasir & Pesanan Online
            </div>
        </div>

        <!-- Total Produk Master -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Katalog Alat</span>
                <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-900 font-mono">
                {{ $totalProducts }} Produk
            </div>
            <div class="text-[11px] text-slate-500 pt-1 border-t border-slate-100">
                <a href="{{ route('products.index') }}" class="text-purple-700 hover:underline font-semibold">Kelola Inventaris &rarr;</a>
            </div>
        </div>

        <!-- Pesanan Online Perlu Diproses -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pesanan Online Baru</span>
                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-amber-700 font-mono">
                {{ $paidOrdersCount }} Pesanan Lunas
            </div>
            <div class="text-[11px] text-slate-500 pt-1 border-t border-slate-100">
                <a href="{{ route('admin.orders.index') }}" class="text-amber-700 hover:underline font-semibold">Proses Pengiriman &rarr;</a>
            </div>
        </div>

    </div>

    <!-- Chart & Low Stock Alert Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Chart.js Revenue Analysis -->
        <div class="lg:col-span-7 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-base text-slate-900">Distribusi Penjualan Bulan Ini</h3>
                    <p class="text-xs text-slate-500">Perbandingan perolehan omset Kasir POS Fisik vs Pesanan Online Toko</p>
                </div>
            </div>
            
            <div class="h-64 relative flex items-center justify-center">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Low Stock Warning Box -->
        <div class="lg:col-span-5 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 rounded-full bg-red-600 animate-pulse"></span>
                    <h3 class="font-bold text-base text-slate-900">Peringatan Batas Stok Gudang</h3>
                </div>
                <a href="{{ route('purchases.create') }}" class="text-xs font-bold text-emerald-800 hover:underline">+ Re-stock</a>
            </div>
            <p class="text-xs text-slate-500">Peralatan outdoor yang tersisa kurang dari batas minimum stok gudang:</p>

            <div class="overflow-hidden rounded-2xl border border-slate-200">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                            <th class="p-2.5">Produk Alat</th>
                            <th class="p-2.5 text-center">Sisa</th>
                            <th class="p-2.5 text-center">Min</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($lowStockProducts as $low)
                        <tr>
                            <td class="p-2.5">
                                <span class="font-bold text-slate-800 block truncate max-w-[180px]">{{ $low->name }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $low->sku }}</span>
                            </td>
                            <td class="p-2.5 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-red-100 text-red-800">
                                    {{ $low->stock }}
                                </span>
                            </td>
                            <td class="p-2.5 text-center text-slate-500 font-bold">{{ $low->minimum_stock }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-4 text-center text-slate-400">Seluruh stok unit perlengkapan masih aman.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $lowStockProducts->links() }}
            </div>
        </div>

    </div>

    <!-- Recent Sales & Recent Online Orders 2-Column -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Recent POS Sales -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-base text-slate-900">Transaksi Kasir POS Terbaru</h3>
                <a href="{{ route('sales.index') }}" class="text-xs font-bold text-emerald-800 hover:underline">Lihat Semua &rarr;</a>
            </div>

            <div class="space-y-3">
                @forelse($recentSales as $sale)
                <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                    <div>
                        <div class="font-mono font-bold text-slate-900">{{ $sale->sale_number }}</div>
                        <div class="text-slate-400 text-[11px]">Kasir: {{ $sale->user->name }} &bull; {{ $sale->sold_at }}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-emerald-800 font-mono text-sm">Rp {{ number_format($sale->total, 0, ',', '.') }}</div>
                        <span class="inline-block px-2 py-0.5 text-[10px] rounded font-bold uppercase {{ $sale->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">{{ $sale->status }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-6 text-slate-400 text-xs">Belum ada transaksi kasir.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Online Customer Orders -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-base text-slate-900">Pesanan Online Customer Terbaru</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-emerald-800 hover:underline">Lihat Semua &rarr;</a>
            </div>

            <div class="space-y-3">
                @forelse($recentOrders as $order)
                <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                    <div>
                        <div class="font-mono font-bold text-slate-900">{{ $order->order_number }}</div>
                        <div class="text-slate-500 text-[11px] font-semibold">{{ $order->customer_name }} &bull; {{ $order->created_at->format('d/m H:i') }}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-emerald-800 font-mono text-sm">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                        <div>{!! $order->status_badge !!}</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-6 text-slate-400 text-xs">Belum ada pesanan online.</div>
                @endforelse
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Kasir POS Toko Fisik', 'Pesanan Online Customer'],
                datasets: [{
                    data: [{{ $salesPosMonth }}, {{ $ordersOnlineMonth }}],
                    backgroundColor: ['#047857', '#d97706'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { family: "'Plus Jakarta Sans', sans-serif", size: 12 },
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
                cutout: '72%',
            }
        });
    });
</script>
@endpush
@endsection
