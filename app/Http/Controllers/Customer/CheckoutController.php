<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Exception;

class CheckoutController extends Controller
{
    /**
     * Tampilkan form checkout pengiriman & pembayaran
     */
    public function index()
    {
        $rawCart = session()->get('cart', []);

        if (empty($rawCart)) {
            return redirect()->route('shop.index')->withErrors([
                'error' => 'Keranjang belanja Anda masih kosong. Silakan pilih produk terlebih dahulu.'
            ]);
        }

        $cartItems = [];
        $grandTotal = 0;

        foreach ($rawCart as $productId => $item) {
            $product = Product::find($productId);
            if ($product && $product->stock > 0) {
                // Harga resmi 100% dari database
                $price = (float) $product->selling_price;
                $quantity = min((int) $item['quantity'], $product->stock);
                $subtotal = $price * $quantity;
                $grandTotal += $subtotal;

                $cartItems[] = [
                    'product'    => $product,
                    'quantity'   => $quantity,
                    'unit_price' => $price,
                    'subtotal'   => $subtotal,
                ];
            }
        }

        if (empty($cartItems)) {
            return redirect()->route('shop.index')->withErrors([
                'error' => 'Produk di keranjang Anda saat ini tidak tersedia atau stok habis.'
            ]);
        }

        $user = auth()->user();

        return view('customer.checkout.index', compact('cartItems', 'grandTotal', 'user'));
    }

    /**
     * Proses pembuatan pesanan baru dengan Payment Gateway
     * Transaksi menggunakan DB Transaction & Server-Side Price Calculation
     */
    public function process(Request $request)
    {
        $request->validate([
            'customer_name'    => 'required|string|max:150',
            'customer_phone'   => 'required|string|min:9|max:20',
            'shipping_address' => 'required|string|min:10',
            'payment_method'   => 'required|in:qris,bank_transfer,ewallet',
            'payment_channel'  => 'required|string|max:50',
            'notes'            => 'nullable|string|max:500',
        ], [
            'customer_name.required'    => 'Nama lengkap penerima wajib diisi.',
            'customer_phone.required'   => 'Nomor telepon/WhatsApp aktif wajib diisi.',
            'shipping_address.required' => 'Alamat lengkap pengiriman wajib diisi.',
            'shipping_address.min'      => 'Mohon tulis alamat pengiriman secara lebih lengkap (min. 10 karakter).',
            'payment_method.required'   => 'Pilih metode pembayaran yang diinginkan.',
            'payment_channel.required'  => 'Pilih channel pembayaran (QRIS, Bank, atau E-Wallet).',
        ]);

        $rawCart = session()->get('cart', []);

        if (empty($rawCart)) {
            return redirect()->route('shop.index')->withErrors([
                'error' => 'Keranjang belanja Anda kosong.'
            ]);
        }

        try {
            $order = DB::transaction(function () use ($request, $rawCart) {
                $grandTotal = 0;
                $validatedItems = [];

                // 1. Validasi stok & ambil harga ASLI dari server DB (Lock for Update untuk cegah race condition)
                foreach ($rawCart as $productId => $item) {
                    $product = Product::lockForUpdate()->find($productId);

                    if (!$product) {
                        throw ValidationException::withMessages([
                            'error' => 'Salah satu produk di keranjang Anda sudah tidak tersedia.'
                        ]);
                    }

                    $qty = (int) $item['quantity'];

                    if ($product->stock < $qty) {
                        throw ValidationException::withMessages([
                            'error' => "Stok produk '{$product->name}' tidak mencukupi (Tersisa: {$product->stock} unit)."
                        ]);
                    }

                    // Kunci keamanan: Harga SELALU dari database Product->selling_price
                    $unitPrice = (float) $product->selling_price;
                    $subtotal = $unitPrice * $qty;
                    $grandTotal += $subtotal;

                    $validatedItems[] = [
                        'product_id' => $product->id,
                        'quantity'   => $qty,
                        'unit_price' => $unitPrice,
                        'subtotal'   => $subtotal,
                    ];
                }

                // 2. Buat Record Order
                $orderNumber = 'ORD-' . date('YmdHis') . '-' . rand(100, 999);
                $order = Order::create([
                    'user_id'          => auth()->id(),
                    'order_number'     => $orderNumber,
                    'customer_name'    => $request->customer_name,
                    'customer_phone'   => $request->customer_phone,
                    'shipping_address' => $request->shipping_address,
                    'notes'            => $request->notes,
                    'total_amount'     => $grandTotal,
                    'status'           => 'pending_payment',
                ]);

                // 3. Buat Record Order Items
                foreach ($validatedItems as $item) {
                    $order->items()->create($item);
                }

                // 4. Generate Payment Gateway Info
                $channel = strtolower($request->payment_channel);
                $vaNumber = null;

                if ($request->payment_method === 'bank_transfer') {
                    // Generate Virtual Account Number realistis berdasarkan bank
                    $bankPrefix = match ($channel) {
                        'bca'     => '8801',
                        'mandiri' => '8902',
                        'bni'     => '8803',
                        'bri'     => '8804',
                        default   => '8800',
                    };
                    $vaNumber = $bankPrefix . date('ymd') . rand(1000, 9999);
                } elseif ($request->payment_method === 'ewallet') {
                    $vaNumber = '08' . rand(10000000, 99999999);
                }

                $txCode = 'PAY-' . strtoupper($channel) . '-' . time() . '-' . rand(10, 99);

                Payment::create([
                    'order_id'         => $order->id,
                    'payment_method'   => $request->payment_method,
                    'payment_channel'  => $channel,
                    'transaction_code' => $txCode,
                    'amount'           => $grandTotal,
                    'va_number'        => $vaNumber,
                    'status'           => 'pending',
                    'notes'            => 'Menunggu verifikasi pembayaran oleh customer di payment gateway',
                ]);

                return $order;
            });

            // Kosongkan keranjang belanja setelah checkout sukses
            session()->forget('cart');

            return redirect()->route('customer.payment.show', $order->id)
                ->with('success', 'Pesanan Anda berhasil dibuat! Silakan selesaikan pembayaran.');

        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
        }
    }
}
