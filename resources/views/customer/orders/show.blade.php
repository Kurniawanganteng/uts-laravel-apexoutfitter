@extends('layouts.customer')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    
    <!-- Top Action Bar (No-Print) -->
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-slate-600 hover:text-slate-900 bg-white px-3.5 py-2 rounded-xl border border-slate-200 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            <span>Kembali ke Riwayat</span>
        </a>

        <div class="flex items-center space-x-2">
            @if($order->status === 'pending_payment')
            <a href="{{ route('customer.payment.show', $order->id) }}" class="inline-flex items-center space-x-1.5 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-md shadow-emerald-900/20 transition">
                <span>Bayar Sekarang</span>
            </a>
            @endif

            <button type="button" onclick="window.print()" class="inline-flex items-center space-x-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-md transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                <span>Cetak Invoice / Struk</span>
            </button>
        </div>
    </div>

    <!-- Official Printable Invoice Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-md p-8 md:p-12 space-y-8">
        
        <!-- Invoice Header -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between pb-6 border-b border-slate-200 gap-4">
            <div>
                <div class="flex items-center space-x-2.5">
                    <x-application-logo class="w-9 h-9 rounded-xl" />
                    <span class="font-black text-xl text-slate-900 tracking-tight">Apex <span class="text-emerald-700">Outfitter</span> Official</span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Penyedia Perlengkapan Gunung, Camping & Ekspedisi Outdoor</p>
                <p class="text-xs text-slate-400">Jl. Rinjani Raya No. 45, Sentra Perlengkapan Petualangan</p>
            </div>
            
            <div class="sm:text-right space-y-1">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">FAKTUR / INVOICE RESMI</span>
                <div class="text-lg font-black font-mono text-slate-900">{{ $order->order_number }}</div>
                <div class="text-xs text-slate-500">Tanggal: {{ $order->created_at->format('d F Y, H:i') }} WIB</div>
                <div class="pt-1">
                    {!! $order->status_badge !!}
                </div>
            </div>
        </div>

        <!-- Customer & Payment Details Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs text-slate-600">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5">
                <span class="font-bold uppercase tracking-wider text-slate-400 text-[10px]">Tujuan Pengiriman</span>
                <div class="font-bold text-sm text-slate-900">{{ $order->customer_name }}</div>
                <div>Telepon / WhatsApp: <strong class="text-slate-800">{{ $order->customer_phone }}</strong></div>
                <div class="text-slate-700 leading-relaxed">{{ $order->shipping_address }}</div>
                @if($order->notes)
                <div class="pt-1 text-[11px] text-slate-500 italic">Catatan: "{{ $order->notes }}"</div>
                @endif
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5">
                <span class="font-bold uppercase tracking-wider text-slate-400 text-[10px]">Informasi Pembayaran (ApexPay)</span>
                @if($order->payment)
                <div>Metode: <strong class="text-slate-900">{{ $order->payment->method_name }}</strong></div>
                <div>Channel: <strong class="text-slate-900 uppercase">{{ $order->payment->payment_channel }}</strong></div>
                <div>Kode Transaksi: <span class="font-mono text-slate-700">{{ $order->payment->transaction_code }}</span></div>
                <div>Status Gateway: <strong class="{{ $order->payment->status === 'success' ? 'text-emerald-700' : 'text-amber-700' }} uppercase">{{ $order->payment->status }}</strong></div>
                @if($order->payment->paid_at)
                <div>Waktu Lunas: <span class="text-slate-700 font-semibold">{{ $order->payment->paid_at->format('d/m/Y H:i:s') }} WIB</span></div>
                @endif
                @else
                <div class="text-slate-400 italic">Data pembayaran belum tercatat.</div>
                @endif
            </div>
        </div>

        <!-- Line Items Table -->
        <div class="overflow-hidden rounded-2xl border border-slate-200">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-700 font-bold border-b border-slate-200">
                        <th class="p-3.5">No.</th>
                        <th class="p-3.5">SKU & Nama Alat Outdoor</th>
                        <th class="p-3.5 text-center">Jumlah</th>
                        <th class="p-3.5 text-right">Harga Satuan (DB)</th>
                        <th class="p-3.5 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($order->items as $idx => $item)
                    <tr>
                        <td class="p-3.5 text-slate-400">{{ $idx + 1 }}</td>
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

        <!-- Total Calculation -->
        <div class="space-y-2 border-t border-slate-200 pt-4">
            <div class="flex justify-between text-xs text-slate-500">
                <span>Subtotal Pembelian Barang:</span>
                <span class="font-mono text-slate-800">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-xs text-slate-500">
                <span>Biaya Layanan Gerbang Pembayaran:</span>
                <span class="font-mono text-emerald-700 font-bold">Rp 0 (Gratis)</span>
            </div>
            <div class="flex justify-between items-center text-base font-extrabold border-t-2 border-slate-900 pt-3">
                <span class="text-slate-900">Total Pembayaran Akhir:</span>
                <span class="text-2xl font-black text-emerald-800 font-mono">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Invoice Footer Stamp -->
        <div class="pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-400 gap-4">
            <div>
                Faktur resmi ini diterbitkan secara otomatis oleh server Apex Outfitter dan sah sebagai bukti kepemilikan transaksi.
            </div>
            <div class="text-right">
                <span class="font-mono font-bold text-slate-600">Terima kasih atas kepercayaan Anda.</span>
            </div>
        </div>

    </div>
</div>
@endsection
