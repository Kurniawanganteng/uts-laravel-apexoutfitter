@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Daftar Produk Master</h1>
    @can('admin-only')
    <a href="{{ route('products.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded font-semibold">+ Tambah Produk</a>
    @endcan
</div>
<div class="bg-white rounded-lg border shadow-sm p-6">
    <form method="GET" action="{{ route('products.index') }}" class="flex space-x-4 mb-6 flex-wrap gap-y-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / SKU..." class="border p-2 rounded w-1/3">
        <select name="category_id" class="border p-2 rounded w-1/4">
            <option value="">-- Semua Kategori --</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-slate-700 text-white px-4 py-2 rounded">Filter</button>
        <a href="{{ route('products.index') }}" class="bg-slate-200 text-slate-700 px-4 py-2 rounded">Reset</a>
    </form>
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b bg-slate-50">
                <th class="p-3">SKU</th><th class="p-3">Nama Produk</th><th class="p-3">Kategori</th>
                <th class="p-3">Harga Beli</th><th class="p-3">Harga Jual</th><th class="p-3">Stok</th>
                @can('admin-only')<th class="p-3">Aksi</th>@endcan
            </tr>
        </thead>
        <tbody>
            @forelse($products as $p)
            <tr class="border-b">
                <td class="p-3 font-mono text-sm">{{ $p->sku }}</td>
                <td class="p-3 font-semibold">{{ $p->name }}</td>
                <td class="p-3">{{ $p->category->name }}</td>
                <td class="p-3">Rp {{ number_format($p->purchase_price, 0, ',', '.') }}</td>
                <td class="p-3 font-semibold text-emerald-700">Rp {{ number_format($p->selling_price, 0, ',', '.') }}</td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded text-xs font-bold {{ $p->stock <= $p->minimum_stock ? 'bg-red-100 text-red-800' : 'bg-emerald-100 text-emerald-800' }}">
                        {{ $p->stock }} Unit
                    </span>
                </td>
                @can('admin-only')
                <td class="p-3 space-x-2">
                    <a href="{{ route('products.edit', $p->id) }}" class="text-blue-600 font-semibold text-sm">Edit</a>
                    <form action="{{ route('products.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Soft delete produk ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 font-semibold text-sm">Hapus</button>
                    </form>
                </td>
                @endcan
            </tr>
            @empty
            <tr><td colspan="7" class="p-4 text-center text-slate-500">Tidak ada produk ditemukan.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection