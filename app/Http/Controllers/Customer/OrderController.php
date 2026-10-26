<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Riwayat Transaksi Pelanggan
     */
    public function index(Request $request)
    {
        $userId = auth()->id();

        $orders = Order::where('user_id', $userId)
            ->with(['items.product', 'payment'])
            ->when($request->search, function ($query, $search) {
                $query->where('order_number', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $counts = [
            'all'             => Order::where('user_id', $userId)->count(),
            'pending_payment' => Order::where('user_id', $userId)->where('status', 'pending_payment')->count(),
            'paid'            => Order::where('user_id', $userId)->where('status', 'paid')->count(),
            'completed'       => Order::where('user_id', $userId)->where('status', 'completed')->count(),
            'cancelled'       => Order::where('user_id', $userId)->where('status', 'cancelled')->count(),
        ];

        return view('customer.orders.index', compact('orders', 'counts'));
    }

    /**
     * Detail Transaksi & Invoice Pelanggan (Struk Pembelian Digital)
     */
    public function show(Order $order)
    {
        if (auth()->id() !== $order->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        $order->load(['items.product', 'payment', 'user']);

        return view('customer.orders.show', compact('order'));
    }

    /**
     * Laporan Belanja & Analisis Pengeluaran Pelanggan (Customer Report)
     */
    public function reports(Request $request)
    {
        $userId = auth()->id();

        $query = Order::where('user_id', $userId)
            ->whereIn('status', ['paid', 'processing', 'completed'])
            ->with('items.product');

        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->latest()->get();

        $totalSpent = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        $averageSpent = $totalOrders > 0 ? $totalSpent / $totalOrders : 0;

        // Hitung total item yang dibeli
        $totalItemsPurchased = 0;
        $purchasedProducts = [];

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $totalItemsPurchased += $item->quantity;
                $prodId = $item->product_id;
                $prodName = $item->product->name ?? 'Produk Dihapus';

                if (!isset($purchasedProducts[$prodId])) {
                    $purchasedProducts[$prodId] = [
                        'name'     => $prodName,
                        'quantity' => 0,
                        'total'    => 0,
                    ];
                }
                $purchasedProducts[$prodId]['quantity'] += $item->quantity;
                $purchasedProducts[$prodId]['total'] += $item->subtotal;
            }
        }

        // Urutkan produk berdasarkan total pengeluaran
        usort($purchasedProducts, fn($a, $b) => $b['total'] <=> $a['total']);

        return view('customer.reports.index', compact(
            'orders',
            'totalSpent',
            'totalOrders',
            'averageSpent',
            'totalItemsPurchased',
            'purchasedProducts'
        ));
    }
}
