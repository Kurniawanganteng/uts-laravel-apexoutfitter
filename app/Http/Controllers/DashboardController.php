<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Jika user adalah customer, arahkan ke storefront / portal pelanggan
        if ($user && $user->isCustomer()) {
            return redirect()->route('shop.index');
        }

        $totalProducts = Product::count();

        // Penjualan POS Kasir
        $salesPosToday = Sale::whereDate('sold_at', today())->where('status', 'completed')->sum('total');
        $salesPosMonth = Sale::whereMonth('sold_at', now()->month)->where('status', 'completed')->sum('total');
        $totalPosTx    = Sale::where('status', 'completed')->count();

        // Pesanan Online Customer (Paid/Completed)
        $ordersOnlineToday = Order::whereDate('created_at', today())->whereIn('status', ['paid', 'processing', 'completed'])->sum('total_amount');
        $ordersOnlineMonth = Order::whereMonth('created_at', now()->month)->whereIn('status', ['paid', 'processing', 'completed'])->sum('total_amount');
        $totalOnlineTx     = Order::whereIn('status', ['paid', 'processing', 'completed'])->count();

        // Gabungan Keseluruhan
        $grandTotalToday = $salesPosToday + $ordersOnlineToday;
        $grandTotalMonth = $salesPosMonth + $ordersOnlineMonth;
        $totalTransactions = $totalPosTx + $totalOnlineTx;

        // Pending Online Orders (Perlu diproses)
        $pendingOrdersCount = Order::where('status', 'pending_payment')->count();
        $paidOrdersCount    = Order::where('status', 'paid')->count();

        // Low Stock Alert dengan eager loading category
        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->paginate(5)
            ->withQueryString();

        // Recent Activity
        $recentSales = Sale::with('user')->latest()->take(5)->get();
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalProducts',
            'grandTotalToday',
            'grandTotalMonth',
            'salesPosMonth',
            'ordersOnlineMonth',
            'totalTransactions',
            'pendingOrdersCount',
            'paidOrdersCount',
            'lowStockProducts',
            'recentSales',
            'recentOrders'
        ));
    }
}
