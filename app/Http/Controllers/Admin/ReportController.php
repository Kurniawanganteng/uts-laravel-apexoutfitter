<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Order;
use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Halaman Utama Pusat Laporan & Analitik Bisnis
     */
    public function index(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate   = $request->end_date ?? now()->toDateString();

        // 1. Penjualan POS Kasir
        $posQuery = Sale::where('status', 'completed')
            ->whereDate('sold_at', '>=', $startDate)
            ->whereDate('sold_at', '<=', $endDate)
            ->with(['user', 'items.product']);
        $posSales = $posQuery->latest()->get();
        $totalPosRevenue = $posSales->sum('total');

        // 2. Pesanan Online Customer
        $onlineQuery = Order::whereIn('status', ['paid', 'processing', 'completed'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->with(['user', 'items.product', 'payment']);
        $onlineOrders = $onlineQuery->latest()->get();
        $totalOnlineRevenue = $onlineOrders->sum('total_amount');

        // 3. Total Omset Keseluruhan (POS + Online)
        $grandTotalRevenue = $totalPosRevenue + $totalOnlineRevenue;

        // 4. Pembelian Stok (Pengeluaran Modal)
        $purchaseQuery = Purchase::where('status', 'completed')
            ->whereDate('purchased_at', '>=', $startDate)
            ->whereDate('purchased_at', '<=', $endDate)
            ->with(['supplier', 'user', 'items.product']);
        $purchases = $purchaseQuery->latest()->get();
        $totalPurchaseCost = $purchases->sum('total');

        // 5. Estimasi Laba Kotor
        $estimatedProfit = $grandTotalRevenue - $totalPurchaseCost;

        // 6. Analisis Produk & Ketersediaan Stok
        $products = Product::with('category')->get();
        $lowStockCount = $products->filter(fn($p) => $p->stock <= $p->minimum_stock)->count();
        $totalInventoryValue = $products->sum(fn($p) => $p->stock * $p->purchase_price);

        // Jika mode cetak diaktifkan
        if ($request->has('print')) {
            return view('admin.reports.print', compact(
                'startDate', 'endDate',
                'posSales', 'totalPosRevenue',
                'onlineOrders', 'totalOnlineRevenue',
                'grandTotalRevenue',
                'purchases', 'totalPurchaseCost',
                'estimatedProfit',
                'products', 'lowStockCount', 'totalInventoryValue'
            ));
        }

        return view('admin.reports.index', compact(
            'startDate', 'endDate',
            'posSales', 'totalPosRevenue',
            'onlineOrders', 'totalOnlineRevenue',
            'grandTotalRevenue',
            'purchases', 'totalPurchaseCost',
            'estimatedProfit',
            'products', 'lowStockCount', 'totalInventoryValue'
        ));
    }
}
