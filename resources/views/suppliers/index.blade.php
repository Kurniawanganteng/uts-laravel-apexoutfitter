@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-bold mb-6">Manajemen Supplier</h1>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-lg border shadow-sm">
        <h2 class="text-lg font-bold mb-4">Tambah Supplier</h2>
        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf
            <div class="mb-3"><label class="block text-sm font-semibold mb-1">Nama Supplier</label><input type="text" name="name" class="w-full border p-2 rounded" required></div>
            <div class="mb-3"><label class="block text-sm font-semibold mb-1">No. Telepon</label><input type="text" name="phone" class="w-full border p-2 rounded" required></div>
            <div class="mb-3"><label class="block text-sm font-semibold mb-1">Email</label><input type="email" name="email" class="w-full border p-2 rounded"></div>
            <div class="mb-3"><label class="block text-sm font-semibold mb-1">Alamat</label><textarea name="address" class="w-full border p-2 rounded" rows="2"></textarea></div>
            <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded font-semibold w-full">Simpan Supplier</button>
        </form>
    </div>
    <div class="md:col-span-2 bg-white p-6 rounded-lg border shadow-sm">
        <h2 class="text-lg font-bold mb-4">Daftar Supplier</h2>
        <table class="w-full text-left border-collapse">
            <thead><tr class="border-b bg-slate-50"><th class="p-3">Nama</th><th class="p-3">Kontak</th><th class="p-3">Alamat</th><th class="p-3">Aksi</th></tr></thead>
            <tbody>
                @foreach($suppliers as $s)
                <tr class="border-b">
                    <td class="p-3 font-semibold">{{ $s->name }}</td>
                    <td class="p-3 text-sm">{{ $s->phone }}<br><span class="text-slate-400">{{ $s->email }}</span></td>
                    <td class="p-3 text-sm">{{ $s->address ?? '-' }}</td>
                    <td class="p-3">
                        <form action="{{ route('suppliers.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus supplier ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 font-semibold text-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $suppliers->links() }}</div>
    </div>
</div>
@endsection