<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ----------------------------------------------------------------------
        // 1. DATA PENGGUNA (USERS & ROLES)
        // ----------------------------------------------------------------------
        $owner = User::create([
            'name'     => 'Apex Owner & Administrator',
            'email'    => 'admin@mail.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        $staff = User::create([
            'name'     => 'Staff Kasir Lapangan',
            'email'    => 'staff@mail.com',
            'password' => Hash::make('password123'),
            'role'     => 'staff',
        ]);

        $customerJack = User::create([
            'name'     => 'Jack Kurniawan',
            'email'    => 'customer@mail.com',
            'password' => Hash::make('password123'),
            'role'     => 'customer',
        ]);

        $customerRian = User::create([
            'name'     => 'Rian Pratama',
            'email'    => 'rian.pratama@mail.com',
            'password' => Hash::make('password123'),
            'role'     => 'customer',
        ]);

        $customerSiti = User::create([
            'name'     => 'Siti Nurhaliza',
            'email'    => 'siti.nurhaliza@mail.com',
            'password' => Hash::make('password123'),
            'role'     => 'customer',
        ]);

        // ----------------------------------------------------------------------
        // 2. KATEGORI PERLENGKAPAN OUTDOOR (CATEGORIES)
        // ----------------------------------------------------------------------
        $catTenda    = Category::create(['name' => 'Tenda & Shelter']);
        $catCarrier  = Category::create(['name' => 'Tas Gunung & Carrier']);
        $catSepatu   = Category::create(['name' => 'Sepatu & Sandal Trekking']);
        $catApparel  = Category::create(['name' => 'Jaket & Apparel Gunung']);
        $catMasak    = Category::create(['name' => 'Alat Masak & Makan Camping']);
        $catTidur    = Category::create(['name' => 'Peralatan Tidur & Matras']);
        $catAksesoris= Category::create(['name' => 'Aksesoris & Navigasi']);

        // ----------------------------------------------------------------------
        // 3. SUPPLIER PERLENGKAPAN OUTDOOR
        // ----------------------------------------------------------------------
        $supEiger = Supplier::create([
            'name'    => 'PT Eigerindo Multi Produk',
            'phone'   => '08112233445',
            'email'   => 'supply@eigerindo.co.id',
            'address' => 'Jl. Kopo No. 438, Kota Bandung, Jawa Barat'
        ]);

        $supConsina = Supplier::create([
            'name'    => 'Consina The Outdoor Lifestyle',
            'phone'   => '08129988776',
            'email'   => 'wholesale@consina.id',
            'address' => 'Jl. Buaran Indah No. 12, Duren Sawit, Jakarta Timur'
        ]);

        $supArei = Supplier::create([
            'name'    => 'Arei Outdoor Gear Indonesia',
            'phone'   => '08137766554',
            'email'   => 'distribusi@arei.co.id',
            'address' => 'Jl. RE Martadinata No. 56, Surakarta, Jawa Tengah'
        ]);

        $supDeuter = Supplier::create([
            'name'    => 'Deuter Indonesia Supply',
            'phone'   => '08185544332',
            'email'   => 'supply@deuter-id.com',
            'address' => 'Kawasan Industri Cikarang Blok A3, Bekasi'
        ]);

        $supTnf = Supplier::create([
            'name'    => 'The North Face Official Distr.',
            'phone'   => '08191122334',
            'email'   => 'official@tnf-distr.id',
            'address' => 'Jl. Senopati No. 88, Kebayoran Baru, Jakarta Selatan'
        ]);

        // ----------------------------------------------------------------------
        // 4. PRODUK MASTER PERLENGKAPAN OUTDOOR (PRODUCTS)
        // ----------------------------------------------------------------------
        $products = [
            [
                'category_id'    => $catTenda->id,
                'sku'            => 'APX-TND-01',
                'name'           => 'Tenda Dome Apex Summit 4P Double Layer Waterproof',
                'description'    => 'Tenda kapasitas 4 orang dengan frame aluminium alloy 8.5mm, outer flysheet 210T ripstop PU 3000mm, seam-taped sealed, vestibule teras depan luas untuk memasak dan logistik.',
                'image'          => 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?auto=format&fit=crop&w=800&q=80',
                'purchase_price' => 780000,
                'selling_price'  => 1250000,
                'stock'          => 16,
                'minimum_stock'  => 4
            ],
            [
                'category_id'    => $catTenda->id,
                'sku'            => 'APX-TND-02',
                'name'           => 'Tenda Ultralight Apex Peak 2P Silicone Coated 1.6kg',
                'description'    => 'Tenda pendakian cepat ultralight kapasitas 2 orang. Berat hanya 1.6kg, bahan 20D silnylon waterproof 4000mm, tiang duralumin aircraft grade 7001.',
                'image'          => 'https://images.unsplash.com/photo-1478827536114-da961b7f86d2?auto=format&fit=crop&w=800&q=80',
                'purchase_price' => 850000,
                'selling_price'  => 1380000,
                'stock'          => 8,
                'minimum_stock'  => 3
            ],
            [
                'category_id'    => $catCarrier->id,
                'sku'            => 'APX-CAR-01',
                'name'           => 'Carrier Apex Highland 65+10L ErgoFit Backsystem',
                'description'    => 'Tas gunung ekspedisi kapasitas 65+10 liter dengan sistem punggung ErgoFit yang dapat diatur sesuai torso. Busa tebal breathable, kompartemen sleeping bag bawah, termasuk Raincover.',
                'image'          => 'https://images.unsplash.com/photo-1499803270242-467f7311582d?auto=format&fit=crop&w=800&q=80',
                'purchase_price' => 620000,
                'selling_price'  => 980000,
                'stock'          => 12,
                'minimum_stock'  => 4
            ],
            [
                'category_id'    => $catCarrier->id,
                'sku'            => 'APX-CAR-02',
                'name'           => 'Ransel Daypack Apex Boulder 32L Waterproof',
                'description'    => 'Ransel harian dan summit attack 32 liter berbahan Cordura 500D water repellent. Dilengkapi slot laptop 15.6 inch, webbing trekking pole, dan kantong botol elastis.',
                'image'          => 'https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?auto=format&fit=crop&w=800&q=80',
                'purchase_price' => 220000,
                'selling_price'  => 365000,
                'stock'          => 3, // Peringatan Stok Kritis (<= 5)
                'minimum_stock'  => 5
            ],
            [
                'category_id'    => $catSepatu->id,
                'sku'            => 'APX-SPT-01',
                'name'           => 'Sepatu Gunung Apex Alpine Mid Waterproof Vibram Sole',
                'description'    => 'Sepatu trekking mid-cut dengan membran waterproof breathable membrane, sol luar Vibram Megagrip anti-selip pada batu basah dan lumpur, toe protection keras anti-benturan.',
                'image'          => 'https://images.unsplash.com/photo-1507818532995-8b91386bb85b?auto=format&fit=crop&w=800&q=80',
                'purchase_price' => 540000,
                'selling_price'  => 890000,
                'stock'          => 14,
                'minimum_stock'  => 4
            ],
            [
                'category_id'    => $catSepatu->id,
                'sku'            => 'APX-SPT-02',
                'name'           => 'Sandal Gunung Apex TrailGrip Ergonomic Strap',
                'description'    => 'Sandal outdoor sol karet mentah traksi tinggi dengan webbing nylon tubular kuat dan bantalan EVA anatomis penopang lekuk telapak kaki.',
                'image'          => 'https://images.unsplash.com/photo-1578848428588-25dda6a5e9cf?auto=format&fit=crop&w=800&q=80',
                'purchase_price' => 95000,
                'selling_price'  => 165000,
                'stock'          => 28,
                'minimum_stock'  => 6
            ],
            [
                'category_id'    => $catApparel->id,
                'sku'            => 'APX-JKT-01',
                'name'           => 'Jaket Gunung 3-in-1 Apex StormShield Gore-Tex',
                'description'    => 'Jaket outdoor dua lapis: outer shell Gore-Tex 3L windproof & 100% waterproof 15.000mm, inner fleece polar tebal yang dapat dilepas pasang sesuai suhu gunung.',
                'image'          => 'https://images.unsplash.com/photo-1578421355558-59788978d500?auto=format&fit=crop&w=800&q=80',
                'purchase_price' => 450000,
                'selling_price'  => 720000,
                'stock'          => 9,
                'minimum_stock'  => 3
            ],
            [
                'category_id'    => $catApparel->id,
                'sku'            => 'APX-JKT-02',
                'name'           => 'Celana Gunung Quickdry Apex Cargo Stretch',
                'description'    => 'Celana trekking panjang bahan despo stretch 4-arah cepat kering. Dilengkapi 6 saku resleting YKK, elastis di pinggang, dan anti-UV UPF 50+.',
                'image'          => 'https://images.unsplash.com/photo-1517445312882-bc9910d016b7?auto=format&fit=crop&w=800&q=80',
                'purchase_price' => 110000,
                'selling_price'  => 195000,
                'stock'          => 22,
                'minimum_stock'  => 5
            ],
            [
                'category_id'    => $catTidur->id,
                'sku'            => 'APX-SB-01',
                'name'           => 'Sleeping Bag Goose Down Apex Arctic -10C Ultralight',
                'description'    => 'Kantong tidur bulu angsa asli fill power 750+, berat hanya 820 gram. Tahan suhu ekstrem hingga -10°C, kompresi sekecil botol air minum 1 liter.',
                'image'          => 'https://images.unsplash.com/photo-1566656346725-883bcf93a1b2?auto=format&fit=crop&w=800&q=80',
                'purchase_price' => 480000,
                'selling_price'  => 790000,
                'stock'          => 2, // Peringatan Stok Kritis (<= 3)
                'minimum_stock'  => 3
            ],
            [
                'category_id'    => $catTidur->id,
                'sku'            => 'APX-MTR-01',
                'name'           => 'Matras Tiup Ultralight Inflatable Pad Built-in Pump',
                'description'    => 'Matras kasur angin tebal 9cm dengan pompa injak bawaan. Menahan dingin tanah (R-value 3.5), berat 650g, bahan nylon 40D TPU tahan bocor.',
                'image'          => 'https://images.unsplash.com/photo-1526778548025-fa2f459cd5c1?auto=format&fit=crop&w=800&q=80',
                'purchase_price' => 175000,
                'selling_price'  => 285000,
                'stock'          => 15,
                'minimum_stock'  => 4
            ],
            [
                'category_id'    => $catMasak->id,
                'sku'            => 'APX-KMP-01',
                'name'           => 'Kompor Camping Ultralight Windproof Apex FireStorm',
                'description'    => 'Kompor gas portabel dengan tameng pelindung angin terintegrasi (windproof), pemantik piezo elektrik instan, daya 3500W, mendidihkan 1L air dalam 3 menit.',
                'image'          => 'https://images.unsplash.com/photo-1789656233730-0d192b35cce0?auto=format&fit=crop&w=800&q=80',
                'purchase_price' => 95000,
                'selling_price'  => 160000,
                'stock'          => 35,
                'minimum_stock'  => 5
            ],
            [
                'category_id'    => $catMasak->id,
                'sku'            => 'APX-NST-01',
                'name'           => 'Cooking Set Nesting Hard Anodized 4-in-1 Apex Chef',
                'description'    => 'Paket panci dan wajan camping aluminium hard anodized tebal anti lengket. Sudah termasuk 2 panci, 2 wajan penutup, mangkuk, centong kayu, dan spons pembersih.',
                'image'          => 'https://images.unsplash.com/photo-1517824806704-9040b037703b?auto=format&fit=crop&w=800&q=80',
                'purchase_price' => 130000,
                'selling_price'  => 225000,
                'stock'          => 20,
                'minimum_stock'  => 5
            ],
            [
                'category_id'    => $catAksesoris->id,
                'sku'            => 'APX-HDL-01',
                'name'           => 'Headlamp LED 1200 Lumens Apex Beam IPX8 Rechargeable',
                'description'    => 'Senter kepala pendakian 1200 lumens dengan sensor gerak tangan (wave sensor), baterai litium type-C rechargeable 2000mAh, tahan air hujan lebat IPX8.',
                'image'          => 'https://images.unsplash.com/photo-1773946734703-596612c4b0fc?auto=format&fit=crop&w=800&q=80',
                'purchase_price' => 85000,
                'selling_price'  => 145000,
                'stock'          => 24,
                'minimum_stock'  => 5
            ],
            [
                'category_id'    => $catAksesoris->id,
                'sku'            => 'APX-TRK-01',
                'name'           => 'Trekking Pole Carbon Fiber Apex QuickLock (Pair)',
                'description'    => 'Sepasang tongkat pendakian berbahan 100% serat karbon ultralight berat 210g per pole. Sistem pengunci QuickLock eksternal kuat dengan handle busa EVA penyerap keringat.',
                'image'          => 'https://images.unsplash.com/photo-1767165020144-ad4685c678a6?auto=format&fit=crop&w=800&q=80',
                'purchase_price' => 140000,
                'selling_price'  => 245000,
                'stock'          => 18,
                'minimum_stock'  => 4
            ],
            [
                'category_id'    => $catTidur->id,
                'sku'            => 'APX-HMK-01',
                'name'           => 'Hammock Double Layer Apex Jungle Parachute Silk 300kg',
                'description'    => 'Tempat tidur gantung kain parasut sutra nilon 210T berdaya tahan beban 300kg. Dilengkapi sepasang tree straps multi-loop tebal dan carabiner baja galvanis.',
                'image'          => 'https://images.unsplash.com/photo-1623387417660-cb2156eeef51?auto=format&fit=crop&w=800&q=80',
                'purchase_price' => 70000,
                'selling_price'  => 125000,
                'stock'          => 30,
                'minimum_stock'  => 5
            ],
            [
                'category_id'    => $catAksesoris->id,
                'sku'            => 'APX-DRY-01',
                'name'           => 'Dry Bag Heavy Duty 20L Roll-Top Waterproof Apex Marine',
                'description'    => 'Tas kedap air 20 liter bahan PVC tarpaulin 500D kelas militer. Sistem penutup roll-top dengan gesper kokoh dan tali selempang yang dapat dilepas.',
                'image'          => 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=800&q=80',
                'purchase_price' => 45000,
                'selling_price'  => 85000,
                'stock'          => 40,
                'minimum_stock'  => 5
            ]
        ];

        $createdProducts = [];
        foreach ($products as $pData) {
            $createdProducts[$pData['sku']] = Product::create($pData);
        }

        // ----------------------------------------------------------------------
        // 5. RIWAYAT PEMBELIAN STOK MASUK (PURCHASES & PURCHASE_ITEMS)
        // ----------------------------------------------------------------------
        // Pembelian 1: Pasokan Tenda & Sleeping Bag dari PT Eigerindo
        $pur1 = Purchase::create([
            'supplier_id'     => $supEiger->id,
            'user_id'         => $owner->id,
            'purchase_number' => 'PO-' . date('Ymd') . '-001',
            'total'           => 12560000,
            'status'          => 'completed',
            'purchased_at'    => Carbon::now()->subDays(12),
        ]);
        PurchaseItem::create([
            'purchase_id' => $pur1->id,
            'product_id'  => $createdProducts['APX-TND-01']->id,
            'quantity'    => 10,
            'unit_cost'   => 780000,
            'subtotal'    => 7800000,
        ]);
        PurchaseItem::create([
            'purchase_id' => $pur1->id,
            'product_id'  => $createdProducts['APX-SB-01']->id,
            'quantity'    => 10,
            'unit_cost'   => 480000,
            'subtotal'    => 4800000,
        ]);

        // Pembelian 2: Pasokan Carrier & Sepatu dari Consina
        $pur2 = Purchase::create([
            'supplier_id'     => $supConsina->id,
            'user_id'         => $owner->id,
            'purchase_number' => 'PO-' . date('Ymd') . '-002',
            'total'           => 8900000,
            'status'          => 'completed',
            'purchased_at'    => Carbon::now()->subDays(5),
        ]);
        PurchaseItem::create([
            'purchase_id' => $pur2->id,
            'product_id'  => $createdProducts['APX-CAR-01']->id,
            'quantity'    => 10,
            'unit_cost'   => 620000,
            'subtotal'    => 6200000,
        ]);
        PurchaseItem::create([
            'purchase_id' => $pur2->id,
            'product_id'  => $createdProducts['APX-SPT-01']->id,
            'quantity'    => 5,
            'unit_cost'   => 540000,
            'subtotal'    => 2700000,
        ]);

        // ----------------------------------------------------------------------
        // 6. RIWAYAT TRANSAKSI KASIR POS TOKO FISIK (SALES & SALE_ITEMS)
        // ----------------------------------------------------------------------
        // Penjualan Kasir 1
        $sale1 = Sale::create([
            'user_id'     => $staff->id,
            'sale_number' => 'POS-' . date('Ymd') . '-0001',
            'total'       => 1575000,
            'status'      => 'completed',
            'sold_at'     => Carbon::now()->subDays(6)->setTime(10, 15),
        ]);
        SaleItem::create([
            'sale_id'    => $sale1->id,
            'product_id' => $createdProducts['APX-TND-01']->id,
            'quantity'   => 1,
            'unit_price' => 1250000,
            'subtotal'   => 1250000,
        ]);
        SaleItem::create([
            'sale_id'    => $sale1->id,
            'product_id' => $createdProducts['APX-HMK-01']->id,
            'quantity'   => 1,
            'unit_price' => 125000,
            'subtotal'   => 125000,
        ]);
        SaleItem::create([
            'sale_id'    => $sale1->id,
            'product_id' => $createdProducts['APX-KMP-01']->id,
            'quantity'   => 1,
            'unit_price' => 160000,
            'subtotal'   => 160000,
        ]);

        // Penjualan Kasir 2
        $sale2 = Sale::create([
            'user_id'     => $staff->id,
            'sale_number' => 'POS-' . date('Ymd') . '-0002',
            'total'       => 1225000,
            'status'      => 'completed',
            'sold_at'     => Carbon::now()->subDays(3)->setTime(14, 30),
        ]);
        SaleItem::create([
            'sale_id'    => $sale2->id,
            'product_id' => $createdProducts['APX-CAR-01']->id,
            'quantity'   => 1,
            'unit_price' => 980000,
            'subtotal'   => 980000,
        ]);
        SaleItem::create([
            'sale_id'    => $sale2->id,
            'product_id' => $createdProducts['APX-TRK-01']->id,
            'quantity'   => 1,
            'unit_price' => 245000,
            'subtotal'   => 245000,
        ]);

        // Penjualan Kasir 3 (Hari ini)
        $sale3 = Sale::create([
            'user_id'     => $owner->id,
            'sale_number' => 'POS-' . date('Ymd') . '-0003',
            'total'       => 1055000,
            'status'      => 'completed',
            'sold_at'     => Carbon::now()->subHours(2),
        ]);
        SaleItem::create([
            'sale_id'    => $sale3->id,
            'product_id' => $createdProducts['APX-SPT-01']->id,
            'quantity'   => 1,
            'unit_price' => 890000,
            'subtotal'   => 890000,
        ]);
        SaleItem::create([
            'sale_id'    => $sale3->id,
            'product_id' => $createdProducts['APX-SPT-02']->id,
            'quantity'   => 1,
            'unit_price' => 165000,
            'subtotal'   => 165000,
        ]);

        // ----------------------------------------------------------------------
        // 7. PESANAN ONLINE CUSTOMER (ORDERS, ORDER_ITEMS & PAYMENTS)
        // ----------------------------------------------------------------------

        // Pesanan 1: Jack Kurniawan (LUNAS / COMPLETED via QRIS)
        $order1 = Order::create([
            'user_id'          => $customerJack->id,
            'order_number'     => 'ORD-' . date('Ymd') . '-0010',
            'customer_name'    => 'Jack Kurniawan',
            'customer_phone'   => '081234567890',
            'shipping_address' => 'Jl. Merdeka No. 10, Jakarta Pusat (Dekat Stasiun Gambir)',
            'notes'            => 'Tolong dicek kembali jahitan seam sealed tenda sebelum dikirim.',
            'total_amount'     => 2040000,
            'status'           => 'completed',
            'created_at'       => Carbon::now()->subDays(4),
        ]);
        OrderItem::create([
            'order_id'   => $order1->id,
            'product_id' => $createdProducts['APX-TND-01']->id,
            'quantity'   => 1,
            'unit_price' => 1250000,
            'subtotal'   => 1250000,
        ]);
        OrderItem::create([
            'order_id'   => $order1->id,
            'product_id' => $createdProducts['APX-SB-01']->id,
            'quantity'   => 1,
            'unit_price' => 790000,
            'subtotal'   => 790000,
        ]);
        Payment::create([
            'order_id'         => $order1->id,
            'payment_method'   => 'qris',
            'payment_channel'  => 'qris',
            'transaction_code' => 'APX-QRIS-' . date('Ymd') . '-9012',
            'amount'           => 2040000,
            'status'           => 'success',
            'paid_at'          => Carbon::now()->subDays(4)->addMinutes(12),
            'notes'            => 'Pembayaran lunas terverifikasi via QRIS Nasional.',
        ]);

        // Pesanan 2: Jack Kurniawan (SEDANG DIPROSES / PROCESSING via Bank BCA)
        $order2 = Order::create([
            'user_id'          => $customerJack->id,
            'order_number'     => 'ORD-' . date('Ymd') . '-0011',
            'customer_name'    => 'Jack Kurniawan',
            'customer_phone'   => '081234567890',
            'shipping_address' => 'Jl. Merdeka No. 10, Jakarta Pusat',
            'notes'            => 'Ukuran sepatu 42 ya kak.',
            'total_amount'     => 1255000,
            'status'           => 'processing',
            'created_at'       => Carbon::now()->subDays(1),
        ]);
        OrderItem::create([
            'order_id'   => $order2->id,
            'product_id' => $createdProducts['APX-SPT-01']->id,
            'quantity'   => 1,
            'unit_price' => 890000,
            'subtotal'   => 890000,
        ]);
        OrderItem::create([
            'order_id'   => $order2->id,
            'product_id' => $createdProducts['APX-CAR-02']->id,
            'quantity'   => 1,
            'unit_price' => 365000,
            'subtotal'   => 365000,
        ]);
        Payment::create([
            'order_id'         => $order2->id,
            'payment_method'   => 'bank_transfer',
            'payment_channel'  => 'bca',
            'transaction_code' => 'APX-BCA-' . date('Ymd') . '-4411',
            'amount'           => 1255000,
            'va_number'        => '8801' . str_pad($order2->id, 8, '0', STR_PAD_LEFT),
            'status'           => 'success',
            'paid_at'          => Carbon::now()->subDays(1)->addMinutes(8),
            'notes'            => 'Pembayaran lunas via BCA Virtual Account.',
        ]);

        // Pesanan 3: Rian Pratama (LUNAS / PAID via GoPay)
        $order3 = Order::create([
            'user_id'          => $customerRian->id,
            'order_number'     => 'ORD-' . date('Ymd') . '-0012',
            'customer_name'    => 'Rian Pratama',
            'customer_phone'   => '081344556677',
            'shipping_address' => 'Perum Dago Asri No. 18, Bandung, Jawa Barat',
            'notes'            => 'Kirim via ekspedisi reguler.',
            'total_amount'     => 1105000,
            'status'           => 'paid',
            'created_at'       => Carbon::now()->subHours(6),
        ]);
        OrderItem::create([
            'order_id'   => $order3->id,
            'product_id' => $createdProducts['APX-JKT-01']->id,
            'quantity'   => 1,
            'unit_price' => 720000,
            'subtotal'   => 720000,
        ]);
        OrderItem::create([
            'order_id'   => $order3->id,
            'product_id' => $createdProducts['APX-NST-01']->id,
            'quantity'   => 1,
            'unit_price' => 225000,
            'subtotal'   => 225000,
        ]);
        OrderItem::create([
            'order_id'   => $order3->id,
            'product_id' => $createdProducts['APX-KMP-01']->id,
            'quantity'   => 1,
            'unit_price' => 160000,
            'subtotal'   => 160000,
        ]);
        Payment::create([
            'order_id'         => $order3->id,
            'payment_method'   => 'ewallet',
            'payment_channel'  => 'gopay',
            'transaction_code' => 'APX-GOPAY-' . date('Ymd') . '-7721',
            'amount'           => 1105000,
            'status'           => 'success',
            'paid_at'          => Carbon::now()->subHours(5)->subMinutes(40),
            'notes'            => 'Pembayaran lunas via GoPay Wallet.',
        ]);

        // Pesanan 4: Siti Nurhaliza (MENUNGGU PEMBAYARAN / PENDING PAYMENT)
        $order4 = Order::create([
            'user_id'          => $customerSiti->id,
            'order_number'     => 'ORD-' . date('Ymd') . '-0013',
            'customer_name'    => 'Siti Nurhaliza',
            'customer_phone'   => '081988776655',
            'shipping_address' => 'Jl. Kaliurang KM 7 No. 24, Sleman, Yogyakarta',
            'notes'            => 'Mohon dipacking rapat anti air.',
            'total_amount'     => 370000,
            'status'           => 'pending_payment',
            'created_at'       => Carbon::now()->subMinutes(45),
        ]);
        OrderItem::create([
            'order_id'   => $order4->id,
            'product_id' => $createdProducts['APX-HMK-01']->id,
            'quantity'   => 1,
            'unit_price' => 125000,
            'subtotal'   => 125000,
        ]);
        OrderItem::create([
            'order_id'   => $order4->id,
            'product_id' => $createdProducts['APX-TRK-01']->id,
            'quantity'   => 1,
            'unit_price' => 245000,
            'subtotal'   => 245000,
        ]);
        Payment::create([
            'order_id'         => $order4->id,
            'payment_method'   => 'qris',
            'payment_channel'  => 'qris',
            'transaction_code' => 'APX-QRIS-' . date('Ymd') . '-1345',
            'amount'           => 370000,
            'status'           => 'pending',
            'notes'            => 'Menunggu verifikasi scan QRIS.',
        ]);
    }
}
