<?php
namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Exception;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $sales = Sale::with('user', 'items.product')
            ->when($request->search, fn($q, $s) => $q->where('sale_number', 'like', "%{$s}%"))
            ->when($request->date,   fn($q, $d) => $q->whereDate('sold_at', $d))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()->paginate(10)->withQueryString();

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|exists:products,id',
            'items.*.quantity'     => 'required|integer|min:1',
        ]);

        try {
            $sale = DB::transaction(function () use ($request) {
                $sale = Sale::create([
                    'user_id'     => auth()->id(),
                    'sale_number' => 'SL-' . date('YmdHis') . '-' . rand(100, 999),
                    'total'       => 0,
                    'status'      => 'completed',
                    'sold_at'     => now(),
                ]);

                $grandTotal = 0;
                foreach ($request->items as $itemData) {
                    $product = Product::lockForUpdate()->findOrFail($itemData['product_id']);
                    $qty = (int) $itemData['quantity'];

                    if ($product->stock < $qty) {
                        throw ValidationException::withMessages([
                            'items' => "Stok '{$product->name}' tidak mencukupi (Sisa: {$product->stock}).",
                        ]);
                    }

                    $unitPrice = $product->selling_price;
                    $subtotal  = $unitPrice * $qty;

                    $sale->items()->create([
                        'product_id' => $product->id,
                        'quantity'   => $qty,
                        'unit_price' => $unitPrice,
                        'subtotal'   => $subtotal,
                    ]);

                    $product->decrement('stock', $qty);
                    $grandTotal += $subtotal;
                }

                $sale->update(['total' => $grandTotal]);
                return $sale;
            });

            return redirect()->route('sales.show', $sale->id)->with('success', 'Transaksi Penjualan Berhasil!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Sale $sale)
    {
        $sale->load('user', 'items.product');
        return view('sales.show', compact('sale'));
    }

    public function cancel(Sale $sale)
    {
        // Alur pembatalan transaksi hanya diizinkan untuk Owner / Administrator
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Akses ditolak: Pembatalan transaksi kasir (POS) hanya dapat dilakukan oleh Owner / Administrator.');
        }

        if ($sale->status === 'cancelled') {
            return back()->with('error', 'Transaksi sudah dibatalkan sebelumnya.');
        }

        try {
            DB::transaction(function () use ($sale) {
                foreach ($sale->items as $item) {
                    $product = Product::withTrashed()->lockForUpdate()->find($item->product_id);
                    if ($product) $product->increment('stock', $item->quantity);
                }
                $sale->update(['status' => 'cancelled']);
            });
            return back()->with('success', 'Transaksi kasir berhasil dibatalkan oleh Owner. Stok telah dikembalikan ke gudang.');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}