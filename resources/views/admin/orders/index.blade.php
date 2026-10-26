@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Title & Stats -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">Manajemen Pesanan Toko Online</h1>
            <p class="text-xs md:text-sm text-slate-500 mt-1">Kelola dan proses seluruh pesanan masuk dari pelanggan toko digital.</p>
        </div>
    </div>

    <!-- Stats Quick Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <a href="{{ route('admin.orders.index') }}" class="p-3.5 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-slate-400 transition block">
            <span class="text-[11px] font-bold text-slate-400 uppercase">Semua</span>
            <div class="text-xl font-black text-slate-900 mt-0.5">{{ $stats['total'] }}</div>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending_payment']) }}" class="p-3.5 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-amber-400 transition block">
            <span class="text-[11px] font-bold text-amber-600 uppercase">Menunggu</span>
            <div class="text-xl font-black text-amber-600 mt-0.5">{{ $stats['pending'] }}</div>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'paid']) }}" class="p-3.5 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-emerald-400 transition block">
            <span class="text-[11px] font-bold text-emerald-600 uppercase">Lunas</span>
            <div class="text-xl font-black text-emerald-600 mt-0.5">{{ $stats['paid'] }}</div>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="p-3.5 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-blue-400 transition block">
            <span class="text-[11px] font-bold text-blue-600 uppercase">Diproses</span>
            <div class="text-xl font-black text-blue-600 mt-0.5">{{ $stats['processing'] }}</div>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="p-3.5 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-purple-400 transition block">
            <span class="text-[11px] font-bold text-purple-600 uppercase">Selesai</span>
            <div class="text-xl font-black text-purple-600 mt-0.5">{{ $stats['completed'] }}</div>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="p-3.5 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-red-400 transition block">
            <span class="text-[11px] font-bold text-red-600 uppercase">Batal</span>
            <div class="text-xl font-black text-red-600 mt-0.5">{{ $stats['cancelled'] }}</div>
        </a>
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-4">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor pesanan, nama customer, atau telepon..." class="flex-1 px-4 py-2 rounded-xl border border-slate-300 focus:border-emerald-500 text-xs outline-none">
            <select name="status" class="px-3 py-2 rounded-xl border border-slate-300 focus:border-emerald-500 text-xs outline-none bg-white">
                <option value="">Semua Status</option>
                <option value="pending_payment" {{ request('status') === 'pending_payment' ? 'selected' : '' }}>Menunggu Bayar</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Sedang Diproses</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-4 py-2 rounded-xl text-xs transition">
                Filter
            </button>
            <a href="{{ route('admin.orders.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-3 py-2 rounded-xl text-xs transition text-center">
                Reset
            </a>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                        <th class="p-3.5">No. Pesanan</th>
                        <th class="p-3.5">Pelanggan</th>
                        <th class="p-3.5">Telepon</th>
                        <th class="p-3.5 text-right">Total Tagihan</th>
                        <th class="p-3.5">Metode Bayar</th>
                        <th class="p-3.5 text-center">Status</th>
                        <th class="p-3.5">Tanggal</th>
                        <th class="p-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="p-3.5 font-mono font-bold text-slate-900">
                            {{ $order->order_number }}
                        </td>
                        <td class="p-3.5 font-bold text-slate-800">{{ $order->customer_name }}</td>
                        <td class="p-3.5 text-slate-500 font-mono">{{ $order->customer_phone }}</td>
                        <td class="p-3.5 text-right font-mono font-extrabold text-emerald-700">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="p-3.5">
                            @if($order->payment)
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 uppercase">
                                {{ $order->payment->payment_channel }}
                            </span>
                            @else
                            <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-center">{!! $order->status_badge !!}</td>
                        <td class="p-3.5 text-slate-400">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-3.5 text-right">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-block text-xs font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg transition">
                                Kelola Detail →
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-slate-400">Belum ada pesanan online yang tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection
