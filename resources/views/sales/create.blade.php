@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Kasir Penjualan Langsung (POS)</h1>
            <p class="text-xs text-slate-500 mt-1">Form kasir toko fisik untuk transaksi langsung dengan pengurangan stok instan.</p>
        </div>
        <a href="{{ route('sales.index') }}" class="text-xs font-bold text-slate-600 bg-white border border-slate-200 px-3.5 py-2 rounded-xl hover:bg-slate-50 transition">
            ← Riwayat Transaksi POS
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('sales.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <span class="text-xs font-bold text-slate-700">Daftar Item Belanja Kasir</span>
                <span class="text-xs text-slate-400">Petugas: <strong>{{ auth()->user()->name }}</strong></span>
            </div>

            <!-- Items Dynamic Container -->
            <div id="items-container" class="space-y-3">
                <div class="flex flex-col sm:flex-row items-center gap-3 item-row p-3 rounded-2xl bg-slate-50 border border-slate-200/70">
                    <div class="flex-1 w-full">
                        <select name="items[0][product_id]" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-medium outline-none bg-white focus:border-emerald-500" required>
                            <option value="">-- Pilih Produk dari Gudang --</option>
                            @foreach($products as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->name }} (SKU: {{ $p->sku }}) — Stok: {{ $p->stock }} unit — Rp {{ number_format($p->selling_price, 0, ',', '.') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full sm:w-32 flex items-center space-x-2">
                        <input type="number" name="items[0][quantity]" min="1" placeholder="Qty" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-center outline-none focus:border-emerald-500" required>
                        <button type="button" class="remove-row p-2 text-red-500 hover:bg-red-50 rounded-xl hidden" title="Hapus baris">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-4 border-t border-slate-100">
                <button type="button" id="add-row" class="inline-flex items-center space-x-1.5 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    <span>+ Tambah Baris Produk</span>
                </button>

                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold px-6 py-3 rounded-xl shadow-md shadow-emerald-600/20 text-xs transition">
                    Proses Transaksi & Cetak Struk
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let idx = 1;
    const container = document.getElementById('items-container');

    document.getElementById('add-row').addEventListener('click', function() {
        const first = container.querySelector('.item-row');
        const row = first.cloneNode(true);
        row.querySelector('select').name = `items[${idx}][product_id]`;
        row.querySelector('select').value = '';
        row.querySelector('input').name = `items[${idx}][quantity]`;
        row.querySelector('input').value = '';
        row.querySelector('.remove-row').classList.remove('hidden');
        container.appendChild(row);
        idx++;
    });

    container.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('.remove-row');
        if (removeBtn) {
            removeBtn.closest('.item-row').remove();
        }
    });
</script>
@endsection
