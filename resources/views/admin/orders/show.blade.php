@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-slate-600 hover:text-slate-900 bg-white px-3.5 py-2 rounded-xl border border-slate-200 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            <span>Kembali ke Daftar Pesanan</span>
        </a>

        <!-- Status Badge -->
        <div>
            {!! $order->status_badge !!}
        </div>
    </div>

    <!-- Main Order Info Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 md:p-8 space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-4">
            <div>
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Nomor Pesanan Online</span>
                <div class="text-xl font-black font-mono text-slate-900">{{ $order->order_number }}</div>
                <div class="text-xs text-slate-400">Dibuat: {{ $order->created_at->format('d F Y, H:i:s') }} WIB</div>
            </div>
            
            <!-- Admin / Owner Status Action Form -->
            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="flex items-center space-x-2 bg-slate-50 p-2 rounded-2xl border border-slate-200">
                @csrf
                <span class="text-xs font-bold text-slate-600 pl-2">Ubah Status:</span>
                <select name="status" class="py-1.5 px-3 rounded-xl border border-slate-300 text-xs font-bold bg-white outline-none">
                    <option value="pending_payment" {{ $order->status === 'pending_payment' ? 'selected' : '' }}>Menunggu Bayar</option>
                    <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Sedang Diproses</option>
                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai Dikirim</option>
                    @can('admin-only')
                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan (Owner)</option>
                    @endcan
                </select>
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-3.5 py-1.5 rounded-xl text-xs transition">
                    Update
                </button>
            </form>
        </div>

        <!-- Customer & Payment Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1">
                <span class="font-bold text-slate-400 uppercase text-[10px] tracking-wider">Data Pengiriman Pelanggan</span>
                <div class="text-sm font-bold text-slate-900">{{ $order->customer_name }}</div>
                <div>Telepon / WhatsApp: <strong class="text-slate-800">{{ $order->customer_phone }}</strong></div>
                <div class="text-slate-700 leading-relaxed">{{ $order->shipping_address }}</div>
                @if($order->notes)
                <div class="pt-1 text-slate-500 italic">Catatan: "{{ $order->notes }}"</div>
                @endif
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1">
                <span class="font-bold text-slate-400 uppercase text-[10px] tracking-wider">Detail Gateway (ApexPay)</span>
                @if($order->payment)
                <div>Metode: <strong class="text-slate-900">{{ $order->payment->method_name }}</strong></div>
                <div>Channel: <strong class="text-slate-900 uppercase">{{ $order->payment->payment_channel }}</strong></div>
                <div>Kode Transaksi: <span class="font-mono text-slate-700">{{ $order->payment->transaction_code }}</span></div>
                @if($order->payment->va_number)
                <div>VA / No. Referensi: <span class="font-mono font-bold text-slate-900">{{ $order->payment->va_number }}</span></div>
                @endif
                <div>Status Gateway: <strong class="{{ $order->payment->status === 'success' ? 'text-emerald-700' : 'text-amber-700' }} uppercase">{{ $order->payment->status }}</strong></div>
                @if($order->payment->paid_at)
                <div>Waktu Pelunasan: <span class="font-semibold text-slate-800">{{ $order->payment->paid_at->format('d/m/Y H:i:s') }}</span></div>
                @endif
                @else
                <div class="text-slate-400 italic">Belum ada data pembayaran.</div>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <div class="overflow-hidden rounded-2xl border border-slate-200">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-700 font-bold border-b border-slate-200">
                        <th class="p-3.5">SKU & Nama Produk Alat</th>
                        <th class="p-3.5 text-center">Jumlah</th>
                        <th class="p-3.5 text-right">Harga Satuan (DB)</th>
                        <th class="p-3.5 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="p-3.5">
                            <span class="font-mono text-[10px] text-slate-400 font-bold block">{{ $item->product->sku ?? '-' }}</span>
                            <span class="font-bold text-slate-800 text-sm">{{ $item->product->name ?? '[Produk Dihapus]' }}</span>
                        </td>
                        <td class="p-3.5 text-center font-bold text-slate-700">{{ $item->quantity }}</td>
                        <td class="p-3.5 text-right font-mono text-slate-700">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="p-3.5 text-right font-mono font-bold text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center pt-2">
            <span class="text-xs text-slate-500">
                @if($order->status === 'cancelled')
                Status: <strong class="text-red-700">Pesanan Dibatalkan oleh Owner (Stok Dikembalikan)</strong>
                @endif
            </span>
            <div class="text-right">
                <span class="text-xs text-slate-500">Total Akumulasi Tagihan:</span>
                <div class="text-2xl font-black text-emerald-800 font-mono">
                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
