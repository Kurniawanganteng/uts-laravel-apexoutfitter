<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Resmi Inventaris & Penjualan - UTS Pemrograman Web</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-size: 11px; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
        }
    </style>
</head>
<body class="bg-white text-slate-900 p-8 max-w-5xl mx-auto font-sans">

    <!-- Top Action Buttons -->
    <div class="mb-6 flex justify-between items-center no-print bg-slate-100 p-4 rounded-xl border border-slate-200">
        <span class="text-xs text-slate-600">Tekan tombol di samping atau gunakan shortcut <strong>Ctrl + P</strong> untuk mencetak dokumen.</span>
        <div class="flex space-x-2">
            <button onclick="window.print()" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2 rounded-lg text-xs">
                Cetak Dokumen / Simpan PDF
            </button>
            <button onclick="window.close()" class="bg-slate-300 hover:bg-slate-400 text-slate-800 font-bold px-4 py-2 rounded-lg text-xs">
                Tutup
            </button>
        </div>
    </div>

    <!-- Official Report Header -->
    <div class="border-b-2 border-slate-900 pb-4 mb-6 text-center space-y-1">
        <h1 class="text-xl font-black uppercase tracking-wider">Apex Outfitter Management System</h1>
        <h2 class="text-sm font-bold text-slate-700">LAPORAN REKAPITULASI PENJUALAN, PESANAN ONLINE & INVENTARIS PERLENGKAPAN OUTDOOR</h2>
        <p class="text-xs text-slate-500">Proyek Ujian Tengah Semester (UTS) - Pemrograman Web Berbasis Laravel & MariaDB</p>
        <p class="text-xs font-semibold text-slate-600 pt-1">
            Periode Laporan: <strong>{{ date('d F Y', strtotime($startDate)) }}</strong> s.d. <strong>{{ date('d F Y', strtotime($endDate)) }}</strong>
        </p>
    </div>

    <!-- Executive Summary Table -->
    <div class="mb-6">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">1. Ringkasan Keuangan & Kinerja Bisnis</h3>
        <table class="w-full text-xs border border-slate-300 border-collapse">
            <tr class="border-b border-slate-200 bg-slate-50">
                <th class="p-2 border-r border-slate-200 text-left">Indikator Kinerja</th>
                <th class="p-2 border-r border-slate-200 text-center">Jumlah Transaksi</th>
                <th class="p-2 text-right">Nilai Rupiah</th>
            </tr>
            <tr class="border-b border-slate-200">
                <td class="p-2 border-r border-slate-200 font-medium">Penjualan Kasir Langsung (POS Kasir)</td>
                <td class="p-2 border-r border-slate-200 text-center">{{ $posSales->count() }} transaksi</td>
                <td class="p-2 text-right font-mono font-bold">Rp {{ number_format($totalPosRevenue, 0, ',', '.') }}</td>
            </tr>
            <tr class="border-b border-slate-200">
                <td class="p-2 border-r border-slate-200 font-medium">Penjualan Pesanan Pelanggan (Toko Online Apex Outfitter)</td>
                <td class="p-2 border-r border-slate-200 text-center">{{ $onlineOrders->count() }} transaksi</td>
                <td class="p-2 text-right font-mono font-bold">Rp {{ number_format($totalOnlineRevenue, 0, ',', '.') }}</td>
            </tr>
            <tr class="border-b border-slate-200 bg-emerald-50/50 font-bold">
                <td class="p-2 border-r border-slate-200">GRAND TOTAL OMSET PENJUALAN</td>
                <td class="p-2 border-r border-slate-200 text-center">{{ $posSales->count() + $onlineOrders->count() }} transaksi</td>
                <td class="p-2 text-right font-mono text-emerald-800 text-sm">Rp {{ number_format($grandTotalRevenue, 0, ',', '.') }}</td>
            </tr>
            <tr class="border-b border-slate-200">
                <td class="p-2 border-r border-slate-200 font-medium">Pengeluaran Pembelian Stok (Modal Masuk dari Supplier)</td>
                <td class="p-2 border-r border-slate-200 text-center">{{ $purchases->count() }} transaksi</td>
                <td class="p-2 text-right font-mono font-bold text-blue-800">Rp {{ number_format($totalPurchaseCost, 0, ',', '.') }}</td>
            </tr>
            <tr class="bg-purple-50/50 font-bold">
                <td class="p-2 border-r border-slate-200">ESTIMASI LABA KOTOR BISNIS</td>
                <td class="p-2 border-r border-slate-200 text-center">-</td>
                <td class="p-2 text-right font-mono text-purple-900 text-sm">Rp {{ number_format($estimatedProfit, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Product Inventory Status -->
    <div class="mb-6">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">2. Status Ketersediaan Stok Produk & Nilai Aset Gudang</h3>
        <table class="w-full text-xs border border-slate-300 border-collapse">
            <thead>
                <tr class="bg-slate-100 border-b border-slate-300 font-bold">
                    <th class="p-2 border-r border-slate-300 text-left">SKU</th>
                    <th class="p-2 border-r border-slate-300 text-left">Nama Produk</th>
                    <th class="p-2 border-r border-slate-300 text-left">Kategori</th>
                    <th class="p-2 border-r border-slate-300 text-right">Harga Beli</th>
                    <th class="p-2 border-r border-slate-300 text-right">Harga Jual</th>
                    <th class="p-2 border-r border-slate-300 text-center">Sisa Stok</th>
                    <th class="p-2 text-right">Total Nilai Aset</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $p)
                <tr class="border-b border-slate-200">
                    <td class="p-2 border-r border-slate-200 font-mono">{{ $p->sku }}</td>
                    <td class="p-2 border-r border-slate-200 font-semibold">{{ $p->name }}</td>
                    <td class="p-2 border-r border-slate-200">{{ $p->category->name }}</td>
                    <td class="p-2 border-r border-slate-200 text-right font-mono">Rp {{ number_format($p->purchase_price, 0, ',', '.') }}</td>
                    <td class="p-2 border-r border-slate-200 text-right font-mono">Rp {{ number_format($p->selling_price, 0, ',', '.') }}</td>
                    <td class="p-2 border-r border-slate-200 text-center font-bold {{ $p->stock <= $p->minimum_stock ? 'text-red-600' : '' }}">
                        {{ $p->stock }} unit
                    </td>
                    <td class="p-2 text-right font-mono font-bold">Rp {{ number_format($p->stock * $p->purchase_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-slate-100 font-bold">
                    <td colspan="6" class="p-2 text-right border-r border-slate-300">TOTAL NILAI ASET GUDANG:</td>
                    <td class="p-2 text-right font-mono text-emerald-800">Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Signature Section -->
    <div class="pt-8 grid grid-cols-2 text-xs text-center">
        <div>
            <p class="text-slate-500">Mengetahui & Memverifikasi,</p>
            <p class="font-bold text-slate-800 mt-0.5">Dosen Pengampu / Penilai UTS</p>
            <div class="h-20"></div>
            <p class="font-bold border-t border-slate-400 inline-block px-8 pt-1 text-slate-800">( .................................................... )</p>
        </div>
        <div>
            <p class="text-slate-500">Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
            <p class="font-bold text-slate-800 mt-0.5">Administrator Sistem Inventaris</p>
            <div class="h-20"></div>
            <p class="font-bold border-t border-slate-400 inline-block px-8 pt-1 text-slate-800">{{ auth()->user()->name ?? 'Administrator Owner' }}</p>
        </div>
    </div>

</body>
</html>
