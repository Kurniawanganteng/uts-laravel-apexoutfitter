@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Riwayat Penjualan (Stock-Out)</h1>
    <a href="{{ route('sales.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded font-semibold">+ Transaksi Baru</a>
</div>
<div class="bg-white rounded-lg border shadow-sm p-6">
    <form method="GET" action="{{ route('sales.index') }}" class="flex space-x-3 mb-6 flex-wrap gap-y-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="No. Transaksi..." class="border p-2 rounded w-1/3">
        <input type="date" name="date" value="{{ request('date') }}" class="border p-2 rounded">
        <select name="status" class="border p-2 rounded">
            <option value="">-- Semua Status --</option>
            <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Completed</option>
            <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option>
        </select>
        <button type="submit" class="bg-slate-700 text-white px-4 py-2 rounded">Filter</button>
        <a href="{{ route('sales.index') }}" class="bg-slate-200 px-4 py-2 rounded">Reset</a>
    </form>
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b bg-slate-50">
                <th class="p-3">No. Transaksi</th><th class="p-3">Kasir</th><th class="p-3">Total</th>
                <th class="p-3">Status</th><th class="p-3">Waktu</th><th class="p-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $s)
            <tr class="border-b">
                <td class="p-3 font-mono text-sm">{{ $s->sale_number }}</td>
                <td class="p-3">{{ $s->user->name }}</td>
                <td class="p-3 font-semibold">Rp {{ number_format($s->total, 0, ',', '.') }}</td>
                <td class="p-3">
                    <span class="px-2 py-1 text-xs rounded font-bold {{ $s->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                        {{ strtoupper($s->status) }}
                    </span>
                </td>
                <td class="p-3 text-sm text-slate-500">{{ $s->sold_at }}</td>
                <td class="p-3 space-x-2">
                    <a href="{{ route('sales.show', $s->id) }}" class="text-blue-600 font-semibold text-sm">Detail</a>
                    @can('admin-only')
                    @if($s->status === 'completed')
                    <form action="{{ route('sales.cancel', $s->id) }}" method="POST" class="inline" onsubmit="return confirm('Batalkan transaksi dan kembalikan stok?')">
                        @csrf
                        <button type="submit" class="text-red-600 font-semibold text-sm">Batalkan</button>
                    </form>
                    @endif
                    @endcan
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-4 text-center text-slate-500">Belum ada transaksi penjualan.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $sales->links() }}</div>
</div>
@endsection