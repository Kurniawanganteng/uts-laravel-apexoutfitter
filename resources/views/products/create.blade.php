@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Tambah Produk Master Baru</h1>
            <p class="text-xs text-slate-500 mt-1">Daftarkan produk baru ke database inventaris sistem.</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-xs font-bold text-slate-600 bg-white border border-slate-200 px-3.5 py-2 rounded-xl hover:bg-slate-50 transition">
            ← Kembali
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('products.store') }}" method="POST" class="space-y-5">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kode SKU Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="sku" value="{{ old('sku') }}" placeholder="Contoh: PRD-ELEK-001" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 text-xs font-mono font-bold outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Produk <span class="text-red-500">*</span></label>
                    <select name="category_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 text-xs font-medium outline-none bg-white" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Produk Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Monitor Gaming LED 24 Inch 165Hz" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 text-xs outline-none" required>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi & Spesifikasi Produk</label>
                <textarea name="description" rows="3" placeholder="Tuliskan spesifikasi detail produk yang akan tampil di katalog toko online..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 text-xs outline-none">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Harga Beli Modal (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="purchase_price" value="{{ old('purchase_price') }}" min="0" placeholder="0" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 text-xs font-mono font-bold outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Harga Jual Resmi (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="selling_price" value="{{ old('selling_price') }}" min="0" placeholder="0" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 text-xs font-mono font-bold text-emerald-700 outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Batas Minimum Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="minimum_stock" value="{{ old('minimum_stock', 5) }}" min="0" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 text-xs font-mono font-bold outline-none" required>
                </div>
            </div>

            <div class="flex items-center space-x-3 pt-4 border-t border-slate-100">
                <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3 px-6 rounded-xl shadow-md shadow-emerald-600/20 transition text-xs">
                    Simpan Produk ke Database
                </button>
                <a href="{{ route('products.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 px-6 rounded-xl text-xs transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
