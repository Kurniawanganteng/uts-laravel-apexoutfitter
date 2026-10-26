<?php
namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $purchases = Purchase::with('supplier', 'user', 'items.product')
            ->when($request->search, fn($q, $s) => $q->where('purchase_number', 'like', "%{$s}%"))
            ->latest()->paginate(10)->withQueryString();

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products  = Product::all();
        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'          => 'required|exists:suppliers,id',
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|exists:products,id',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.unit_cost'    => 'required|numeric|min:0',
        ]);

        try {
            $purchase = DB::transaction(function () use ($request) {
                $purchase = Purchase::create([
                    'supplier_id'     => $request->supplier_id,
                    'user_id'         => auth()->id(),
                    'purchase_number' => 'PUR-' . date('YmdHis') . '-' . rand(100, 999),
                    'total'           => 0,
                    'status'          => 'completed',
                    'purchased_at'    => now(),
                ]);

                $grandTotal = 0;
                foreach ($request->items as $item) {
                    $product  = Product::lockForUpdate()->findOrFail($item['product_id']);
                    $qty      = (int) $item['quantity'];
                    $unitCost = (float) $item['unit_cost'];
                    $subtotal = $unitCost * $qty;

                    $purchase->items()->create([
                        'product_id' => $product->id,
                        'quantity'   => $qty,
                        'unit_cost'  => $unitCost,
                        'subtotal'   => $subtotal,
                    ]);

                    $product->increment('stock', $qty);
                    $grandTotal += $subtotal;
                }

                $purchase->update(['total' => $grandTotal]);
                return $purchase;
            });

            return redirect()->route('purchases.show', $purchase->id)->with('success', 'Pembelian Stok Berhasil!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load('supplier', 'user', 'items.product');
        return view('purchases.show', compact('purchase'));
    }
}