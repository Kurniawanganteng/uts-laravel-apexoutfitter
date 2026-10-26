@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-bold mb-6">Manajemen Kategori</h1>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-lg border shadow-sm">
        <h2 class="text-lg font-bold mb-4">Tambah Kategori</h2>
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Nama Kategori</label>
                <input type="text" name="name" class="w-full border p-2 rounded" required>
            </div>
            <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded font-semibold w-full">Simpan Kategori</button>
        </form>
    </div>
    <div class="md:col-span-2 bg-white p-6 rounded-lg border shadow-sm">
        <h2 class="text-lg font-bold mb-4">Daftar Kategori</h2>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b bg-slate-50">
                    <th class="p-3">ID</th><th class="p-3">Nama Kategori</th><th class="p-3">Jumlah Produk</th><th class="p-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $c)
                <tr class="border-b">
                    <td class="p-3">{{ $c->id }}</td>
                    <td class="p-3 font-semibold">{{ $c->name }}</td>
                    <td class="p-3">{{ $c->products_count }} Produk</td>
                    <td class="p-3">
                        <form action="{{ route('categories.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 font-semibold text-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $categories->links() }}</div>
    </div>
</div>
@endsection