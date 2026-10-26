<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderManagementController extends Controller
{
    /**
     * Daftar seluruh pesanan online dari customer
     */
    public function index(Request $request)
    {
        $orders = Order::with(['user', 'items.product', 'payment'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                      ->orWhere('customer_name', 'like', "%{$search}%")
                      ->orWhere('customer_phone', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total'      => Order::count(),
            'pending'    => Order::where('status', 'pending_payment')->count(),
            'paid'       => Order::where('status', 'paid')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed'  => Order::where('status', 'completed')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Detail pesanan pelanggan untuk admin
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'payment']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update status pesanan (diproses, selesai, dibatalkan)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending_payment,paid,processing,completed,cancelled',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Alur pembatalan transaksi hanya diizinkan untuk Owner / Administrator
        if ($newStatus === 'cancelled' && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Akses ditolak: Pembatalan pesanan pelanggan hanya berhak dilakukan oleh Owner / Administrator.');
        }

        try {
            DB::transaction(function () use ($order, $oldStatus, $newStatus) {
                // Jika dibatalkan dari status paid/processing, kembalikan stok secara aman
                if ($newStatus === 'cancelled' && in_array($oldStatus, ['paid', 'processing'])) {
                    foreach ($order->items as $item) {
                        $product = Product::withTrashed()->lockForUpdate()->find($item->product_id);
                        if ($product) {
                            $product->increment('stock', $item->quantity);
                        }
                    }

                    if ($order->payment) {
                        $order->payment->update([
                            'status' => 'failed',
                            'notes'  => 'Dibatalkan oleh Owner / Administrator.',
                        ]);
                    }
                }

                $order->update(['status' => $newStatus]);
            });

            return back()->with('success', "Status pesanan {$order->order_number} berhasil diperbarui menjadi " . strtoupper(str_replace('_', ' ', $newStatus)));
        } catch (Exception $e) {
            return back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }
}
