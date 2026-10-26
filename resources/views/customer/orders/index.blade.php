@extends('layouts.customer')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">Riwayat Transaksi Saya</h1>
            <p class="text-xs md:text-sm text-slate-500 mt-1">Pantau status pesanan, pembayaran digital, dan unduh faktur/struk belanja Anda.</p>
        </div>
        <a href="{{ route('shop.index') }}" class="inline-flex items-center space-x-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md shadow-emerald-600/20 transition self-start sm:self-auto">
            <span>+ Belanja Lagi</span>
        </a>
    </div>

    <!-- Status Tabs -->
    <div class="flex items-center space-x-2 overflow-x-auto pb-1 text-xs font-semibold">
        <a href="{{ route('customer.orders.index') }}" class="px-4 py-2 rounded-xl whitespace-nowrap transition {{ !request('status') ? 'bg-slate-900 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Semua ({{ $counts['all'] }})
        </a>
        <a href="{{ route('customer.orders.index', ['status' => 'pending_payment']) }}" class="px-4 py-2 rounded-xl whitespace-nowrap transition {{ request('status') === 'pending_payment' ? 'bg-amber-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Menunggu Bayar ({{ $counts['pending_payment'] }})
        </a>
        <a href="{{ route('customer.orders.index', ['status' => 'paid']) }}" class="px-4 py-2 rounded-xl whitespace-nowrap transition {{ request('status') === 'paid' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Sudah Dibayar ({{ $counts['paid'] }})
        </a>
        <a href="{{ route('customer.orders.index', ['status' => 'completed']) }}" class="px-4 py-2 rounded-xl whitespace-nowrap transition {{ request('status') === 'completed' ? 'bg-purple-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Selesai ({{ $counts['completed'] }})
        </a>
        <a href="{{ route('customer.orders.index', ['status' => 'cancelled']) }}" class="px-4 py-2 rounded-xl whitespace-nowrap transition {{ request('status') === 'cancelled' ? 'bg-red-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Dibatalkan ({{ $counts['cancelled'] }})
        </a>
    </div>

    <!-- Orders List -->
    <div class="space-y-4">
        @forelse($orders as $order)
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 hover:shadow-md transition space-y-4">
            
            <!-- Order Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 gap-2">
                <div class="flex items-center space-x-3">
                    <span class="font-mono font-bold text-sm text-slate-900">{{ $order->order_number }}</span>
                    <span class="text-slate-300">•</span>
                    <span class="text-xs text-slate-400">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                </div>
                <div class="flex items-center space-x-2">
                    {!! $order->status_badge !!}
                </div>
            </div>

            <!-- Items Brief Preview -->
            <div class="space-y-2">
                @foreach($order->items as $item)
                <div class="flex items-center justify-between text-xs text-slate-700">
                    <div class="flex items-center space-x-2 truncate">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span class="font-medium truncate">{{ $item->product->name ?? 'Produk Dihapus' }}</span>
                        <span class="text-slate-400">({{ $item->quantity }}x)</span>
                    </div>
                    <span class="font-semibold text-slate-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>

            <!-- Order Footer & Action -->
            <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center space-x-3">
                    @if($order->payment)
                    <span class="text-[11px] font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">
                        Metode: {{ $order->payment->method_name }} ({{ strtoupper($order->payment->payment_channel) }})
                    </span>
                    @endif
                    <div class="text-xs text-slate-500">
                        Total Tagihan: <strong class="text-base text-emerald-700 font-extrabold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                    </div>
                </div>

                <div class="flex items-center space-x-2 self-end sm:self-auto">
                    @if($order->status === 'pending_payment')
                    <a href="{{ route('customer.payment.show', $order->id) }}" class="inline-flex items-center space-x-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-md shadow-emerald-600/20 transition">
                        <span>Bayar Sekarang</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </a>
                    @else
                    <a href="{{ route('customer.orders.show', $order->id) }}" class="inline-flex items-center space-x-1 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold px-4 py-2 rounded-xl transition">
                        <span>Lihat Faktur / Struk</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                    @endif
                </div>
            </div>

        </div>
        @empty
        <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center max-w-md mx-auto space-y-3">
            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            </div>
            <h3 class="text-base font-bold text-slate-800">Belum Ada Transaksi</h3>
            <p class="text-xs text-slate-500">Anda belum memiliki riwayat transaksi untuk filter ini.</p>
            <div class="pt-2">
                <a href="{{ route('shop.index') }}" class="inline-block bg-emerald-600 text-white text-xs font-bold px-4 py-2 rounded-xl">Mulai Belanja</a>
            </div>
        </div>
        @endforelse
    </div>

    <div class="pt-2">
        {{ $orders->links() }}
    </div>

</div>
@endsection
