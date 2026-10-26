<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Tampilkan halaman keranjang belanja
     * Harga dan ketersediaan stok SELALU diambil langsung dari database
     */
    public function index()
    {
        $rawCart = session()->get('cart', []);
        $cartItems = [];
        $grandTotal = 0;
        $totalItems = 0;

        foreach ($rawCart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                // Harga resmi dari database server, bukan dari session/client
                $price = (float) $product->selling_price;
                $quantity = min((int) $item['quantity'], $product->stock); // pastikan tidak melebihi stok

                if ($quantity > 0) {
                    $subtotal = $price * $quantity;
                    $grandTotal += $subtotal;
                    $totalItems += $quantity;

                    $cartItems[$productId] = [
                        'product'    => $product,
                        'quantity'   => $quantity,
                        'unit_price' => $price,
                        'subtotal'   => $subtotal,
                        'max_stock'  => $product->stock,
                    ];
                }
            }
        }

        return view('customer.cart.index', compact('cartItems', 'grandTotal', 'totalItems'));
    }

    /**
     * Tambah produk ke keranjang belanja
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quantity = (int) ($request->quantity ?? 1);

        // Validasi stok langsung dari database
        if ($product->stock < $quantity) {
            return back()->withErrors([
                'error' => "Mohon maaf, stok produk '{$product->name}' hanya tersisa {$product->stock} unit."
            ]);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $newQuantity = $cart[$product->id]['quantity'] + $quantity;
            if ($product->stock < $newQuantity) {
                return back()->withErrors([
                    'error' => "Maksimal stok yang tersedia untuk produk ini adalah {$product->stock} unit."
                ]);
            }
            $cart[$product->id]['quantity'] = $newQuantity;
        } else {
            $cart[$product->id] = [
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('customer.cart.index')->with('success', "Produk '{$product->name}' berhasil ditambahkan ke keranjang!");
    }

    /**
     * Update jumlah quantity produk di keranjang
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $newQty = (int) $request->quantity;

        if ($product->stock < $newQty) {
            return back()->withErrors([
                'error' => "Jumlah melebihi batas stok tersedia ({$product->stock} unit)."
            ]);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $newQty;
            session()->put('cart', $cart);
        }

        return redirect()->route('customer.cart.index')->with('success', 'Jumlah produk berhasil diperbarui.');
    }

    /**
     * Hapus satu item dari keranjang
     */
    public function remove(Product $product)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('customer.cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }

    /**
     * Kosongkan seluruh keranjang belanja
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('customer.cart.index')->with('success', 'Keranjang belanja dikosongkan.');
    }
}
