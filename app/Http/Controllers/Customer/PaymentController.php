<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Exception;

class PaymentController extends Controller
{
    /**
     * Tampilkan Halaman Payment Gateway Simulator (JavaPay Gateway)
     */
    public function show(Order $order)
    {
        // Otorisasi: hanya pemilik pesanan atau admin yang dapat mengakses
        if (auth()->id() !== $order->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak: Anda tidak memiliki akses ke pesanan ini.');
        }

        $order->load('items.product', 'payment');

        if (!$order->payment) {
            return redirect()->route('customer.orders.index')->withErrors(['error' => 'Data pembayaran tidak ditemukan.']);
        }

        return view('customer.payment.show', compact('order'));
    }

    /**
     * Simulasi Pembayaran Sukses (Payment Gateway Webhook / Callback Simulator)
     * Mengurangi stok produk secara atomik dan mengubah status order menjadi PAID
     */
    public function simulatePay(Request $request, Order $order)
    {
        if (auth()->id() !== $order->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        if ($order->status !== 'pending_payment') {
            return back()->withErrors(['error' => 'Pesanan ini sudah diproses atau dibatalkan sebelumnya.']);
        }

        try {
            DB::transaction(function () use ($order) {
                // 1. Lock produk dan kurangi stok secara aman
                foreach ($order->items as $item) {
                    $product = Product::lockForUpdate()->find($item->product_id);

                    if (!$product) {
                        throw new Exception("Produk #{$item->product_id} tidak ditemukan.");
                    }

                    if ($product->stock < $item->quantity) {
                        throw new Exception("Stok untuk produk '{$product->name}' tidak mencukupi (Tersisa {$product->stock} unit). Pembayaran gagal diproses.");
                    }

                    // Kurangi stok produk secara resmi
                    $product->decrement('stock', $item->quantity);
                }

                // 2. Update status pembayaran menjadi SUCCESS
                if ($order->payment) {
                    $order->payment->update([
                        'status'  => 'success',
                        'paid_at' => now(),
                        'notes'   => 'Pembayaran berhasil diverifikasi via ApexPay Gateway Simulator.',
                    ]);
                }

                // 3. Update status pesanan menjadi PAID
                $order->update([
                    'status' => 'paid',
                ]);
            });

            return redirect()->route('customer.orders.show', $order->id)
                ->with('success', 'Pembayaran berhasil diverifikasi. Pesanan Anda telah lunas dan sedang diproses.');

        } catch (Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Batalkan pesanan dan pembayaran (Khusus Owner / Administrator)
     */
    public function cancel(Order $order)
    {
        // Alur pembatalan transaksi hanya diizinkan untuk Owner / Administrator
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Akses ditolak: Pembatalan transaksi pesanan hanya dapat diproses oleh Owner / Administrator.');
        }

        if ($order->status === 'cancelled') {
            return back()->with('error', 'Pesanan sudah dibatalkan sebelumnya.');
        }

        try {
            DB::transaction(function () use ($order) {
                // Jika pesanan sudah sempat dibayar, kembalikan stok
                if ($order->status === 'paid' || $order->status === 'processing') {
                    foreach ($order->items as $item) {
                        $product = Product::withTrashed()->lockForUpdate()->find($item->product_id);
                        if ($product) {
                            $product->increment('stock', $item->quantity);
                        }
                    }
                }

                if ($order->payment) {
                    $order->payment->update([
                        'status' => 'failed',
                        'notes'  => 'Dibatalkan oleh Owner / Administrator.',
                    ]);
                }

                $order->update(['status' => 'cancelled']);
            });

            return redirect()->route('customer.orders.index')->with('success', 'Pesanan berhasil dibatalkan oleh Owner.');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal membatalkan pesanan: ' . $e->getMessage());
        }
    }
}
