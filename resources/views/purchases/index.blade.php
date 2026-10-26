@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Riwayat Pembelian Stok (Stock-In)</h1>
    <a href="{{ route('purchases.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded font-semibold">+ Pembelian Baru</a>
</div>
<div class="bg-white rounded-lg border shadow-sm p-6">
    <form method="GET" action="{{ route('purchases.index') }}" class="flex space-x-3 mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="No. Transaksi..." class="border p-2 rounded w-1/3">
        <button type="submit" class="bg-slate-700 text-white px-4 py-2 rounded">Filter</button>
        <a href="{{ route('purchases.index') }}" class="bg-slate-200 px-4 py-2 rounded">Reset</a>
    </form>
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b bg-slate-50">
                <th class="p-3">No. Transaksi</th><th class="p-3">Supplier</th><th class="p-3">Petugas</th>
                <th class="p-3">Total</th><th class="p-3">Waktu</th><th class="p-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchases as $p)
            <tr class="border-b">
                <td class="p-3 font-mono text-sm">{{ $p->purchase_number }}</td>
                <td class="p-3 font-semibold">{{ $p->supplier->name }}</td>
                <td class="p-3">{{ $p->user->name }}</td>
                <td class="p-3 font-semibold">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                <td class="p-3 text-sm text-slate-500">{{ $p->purchased_at }}</td>
                <td class="p-3"><a href="{{ route('purchases.show', $p->id) }}" class="text-blue-600 font-semibold text-sm">Detail</a></td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-4 text-center text-slate-500">Belum ada data pembelian.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $purchases->links() }}</div>
</div>
@endsection