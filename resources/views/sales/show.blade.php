@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('sales.index') }}" class="text-xs font-bold text-slate-600 bg-white border border-slate-200 px-3.5 py-2 rounded-xl hover:bg-slate-50 transition">
            &larr; Riwayat Penjualan POS
        </a>
        <div class="flex items-center space-x-2">
            @can('admin-only')
            @if($sale->status !== 'cancelled')
            <form action="{{ route('sales.cancel', $sale->id) }}" method="POST" onsubmit="return confirm('Konfirmasi Owner: Batalkan transaksi kasir ini? Stok akan otomatis dikembalikan ke inventaris gudang.')">
                @csrf
                <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 text-xs font-bold px-3.5 py-2 rounded-xl transition">
                    Batalkan Transaksi (Owner)
                </button>
            </form>
            @endif
            @endcan

            <button type="button" onclick="window.print()" class="inline-flex items-center space-x-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                <span>Cetak Struk Kasir</span>
            </button>
        </div>
    </div>

    <!-- Official POS Receipt -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
        <div class="flex justify-between items-start border-b border-slate-200 pb-4">
            <div>
                <h2 class="text-lg font-black text-slate-900">STRUK TRANSAKSI KASIR (POS)</h2>
                <div class="text-xs font-mono font-bold text-slate-500 mt-0.5">{{ $sale->sale_number }}</div>
                <div class="text-[11px] text-slate-400 mt-1">Apex Outfitter POS & Outdoor Inventory</div>
            </div>
            <div class="text-right text-xs text-slate-500 space-y-1">
                <div>Kasir: <strong class="text-slate-800">{{ $sale->user->name }}</strong></div>
                <div>{{ $sale->sold_at }}</div>
                <span class="inline-block px-2 py-0.5 text-[10px] rounded font-bold uppercase {{ $sale->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">{{ $sale->status }}</span>
            </div>
        </div>

        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 text-slate-600 font-bold">
                    <th class="p-2.5">Produk Alat Outdoor</th>
                    <th class="p-2.5 text-center">Qty</th>
                    <th class="p-2.5 text-right">Harga Satuan</th>
                    <th class="p-2.5 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($sale->items as $item)
                <tr>
                    <td class="p-2.5 font-bold text-slate-800">{{ $item->product->name ?? '[Produk Dihapus]' }}</td>
                    <td class="p-2.5 text-center font-bold text-slate-700">{{ $item->quantity }}</td>
                    <td class="p-2.5 text-right font-mono text-slate-600">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="p-2.5 text-right font-mono font-bold text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right text-base font-extrabold border-t-2 border-slate-900 pt-3">
            Total Pembayaran: <span class="text-emerald-800 text-xl font-mono">Rp {{ number_format($sale->total, 0, ',', '.') }}</span>
        </div>

        @if($sale->status === 'cancelled')
        <div class="p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs text-center font-bold">
            Transaksi ini telah DIBATALKAN oleh Owner. Kuantitas stok telah dikembalikan ke inventaris gudang.
        </div>
        @endif

        <div class="text-center text-[11px] text-slate-400 pt-4 border-t border-slate-100">
            Terima kasih telah berbelanja di Apex Outfitter. Pastikan memeriksa kelengkapan alat sebelum meninggalkan kasir.
        </div>
    </div>
</div>
@endsection
