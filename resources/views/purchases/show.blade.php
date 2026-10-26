@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('purchases.index') }}" class="text-xs font-bold text-slate-600 bg-white border border-slate-200 px-3.5 py-2 rounded-xl hover:bg-slate-50 transition">
            ← Riwayat Pembelian
        </a>
        <button type="button" onclick="window.print()" class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-4 py-2 rounded-xl transition">
            Cetak Bukti Masuk Stok
        </button>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
        <div class="flex justify-between items-start border-b border-slate-200 pb-4">
            <div>
                <h2 class="text-lg font-black text-slate-900">BUKTI PENERIMAAN BARANG DARI SUPPLIER</h2>
                <div class="text-xs font-mono font-bold text-slate-500 mt-0.5">{{ $purchase->purchase_number }}</div>
                <div class="text-[11px] text-slate-400 mt-1">Apex Outfitter — Gudang Inbound</div>
            </div>
            <div class="text-right text-xs text-slate-500 space-y-1">
                <div>Supplier: <strong class="text-slate-800">{{ $purchase->supplier->name }}</strong></div>
                <div>Petugas: <strong class="text-slate-800">{{ $purchase->user->name }}</strong></div>
                <div>{{ $purchase->purchased_at }}</div>
            </div>
        </div>

        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 text-slate-600 font-bold">
                    <th class="p-2.5">Produk</th>
                    <th class="p-2.5 text-center">Qty Masuk</th>
                    <th class="p-2.5 text-right">Harga Modal Beli</th>
                    <th class="p-2.5 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($purchase->items as $item)
                <tr>
                    <td class="p-2.5 font-bold text-slate-800">{{ $item->product->name ?? '[Produk Dihapus]' }}</td>
                    <td class="p-2.5 text-center font-bold text-slate-700">+{{ $item->quantity }} unit</td>
                    <td class="p-2.5 text-right font-mono text-slate-600">Rp {{ number_format($item->unit_cost, 0, ',', '.') }}</td>
                    <td class="p-2.5 text-right font-mono font-bold text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right text-base font-extrabold border-t-2 border-slate-900 pt-3">
            Total Pembelian Modal: <span class="text-blue-700 text-xl font-mono">Rp {{ number_format($purchase->total, 0, ',', '.') }}</span>
        </div>

        <div class="text-center text-[11px] text-slate-400 pt-4 border-t border-slate-100">
            Bukti ini merupakan tanda terima resmi stok masuk ke inventaris sistem.
        </div>
    </div>
</div>
@endsection
