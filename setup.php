<<?php

// ==============================================================================
// SKRIP GENERATOR ULTIMATE UTS LARAVEL - INVENTORY & SALES MANAGEMENT SYSTEM
// ==============================================================================

echo "🚀 Menggenerasi SELURUH file project UTS Laravel secara lengkap...\n\n";

$files = [];

// ------------------------------------------------------------------------------
// 1. ROUTES (routes/web.php)
// ------------------------------------------------------------------------------
$files['routes/web.php'] = <<<'PHP'
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;

Route::redirect('/', '/dashboard');

Route::middleware(['auth'])->group(function () {
    // Akses Bersama (Staff & Admin)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    
    // Transaksi Penjualan (Sales)
    Route::resource('sales', SaleController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('/sales/{sale}/cancel', [SaleController::class, 'cancel'])->name('sales.cancel');
    
    // Transaksi Pembelian (Purchases)
    Route::resource('purchases', PurchaseController::class)->only(['index', 'create', 'store', 'show']);

    // Khusus Admin (Dibatasi Server-Side via Gate 'admin-only')
    Route::middleware(['can:admin-only'])->group(function () {
        Route::resource('products', ProductController::class)->except(['index', 'show']);
        Route::resource('categories', CategoryController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('users', UserController::class);
    });
});

require __DIR__.'/auth.php';
PHP;

// ------------------------------------------------------------------------------
// 2. GATE AUTHORIZATION (app/Providers/AppServiceProvider.php)
// ------------------------------------------------------------------------------
$files['app/Providers/AppServiceProvider.php'] = <<<'PHP'
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Gate::define('admin-only', function (User $user) {
            return $user->role === 'admin';
        });
    }
}
PHP;

// ------------------------------------------------------------------------------
// 3. MIGRATION (database/migrations/2026_01_01_000000_create_inventory_tables.php)
// ------------------------------------------------------------------------------
$files['database/migrations/2026_01_01_000000_create_inventory_tables.php'] = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['admin', 'staff'])->default('staff')->after('email');
            });
        }

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->string('sku')->unique();
            $table->string('name');
            $table->decimal('purchase_price', 12, 2);
            $table->decimal('selling_price', 12, 2);
            $table->integer('stock')->default(0);
            $table->integer('minimum_stock')->default(5);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('restrict');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->string('purchase_number')->unique();
            $table->decimal('total', 12, 2)->default(0);
            $table->enum('status', ['completed', 'cancelled'])->default('completed');
            $table->timestamp('purchased_at');
            $table->timestamps();
        });

        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->integer('quantity');
            $table->decimal('unit_cost', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->string('sale_number')->unique();
            $table->decimal('total', 12, 2)->default(0);
            $table->enum('status', ['completed', 'cancelled'])->default('completed');
            $table->timestamp('sold_at');
            $table->timestamps();
        });

        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('products');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('categories');
    }
};
PHP;

// ------------------------------------------------------------------------------
// 4. ELOQUENT MODELS
// ------------------------------------------------------------------------------
$files['app/Models/Category.php'] = <<<'PHP'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    protected $fillable = ['name'];
    public function products() { return $this->hasMany(Product::class); }
}
PHP;

$files['app/Models/Supplier.php'] = <<<'PHP'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model {
    protected $fillable = ['name', 'phone', 'email', 'address'];
    public function purchases() { return $this->hasMany(Purchase::class); }
}
PHP;

$files['app/Models/Product.php'] = <<<'PHP'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model {
    use SoftDeletes;
    protected $fillable = ['category_id', 'sku', 'name', 'purchase_price', 'selling_price', 'stock', 'minimum_stock'];
    public function category() { return $this->belongsTo(Category::class); }
    public function saleItems() { return $this->hasMany(SaleItem::class); }
    public function purchaseItems() { return $this->hasMany(PurchaseItem::class); }
}
PHP;

$files['app/Models/Sale.php'] = <<<'PHP'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model {
    protected $fillable = ['user_id', 'sale_number', 'total', 'status', 'sold_at'];
    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(SaleItem::class); }
}
PHP;

$files['app/Models/SaleItem.php'] = <<<'PHP'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model {
    protected $fillable = ['sale_id', 'product_id', 'quantity', 'unit_price', 'subtotal'];
    public function sale() { return $this->belongsTo(Sale::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
PHP;

$files['app/Models/Purchase.php'] = <<<'PHP'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model {
    protected $fillable = ['supplier_id', 'user_id', 'purchase_number', 'total', 'status', 'purchased_at'];
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(PurchaseItem::class); }
}
PHP;

$files['app/Models/PurchaseItem.php'] = <<<'PHP'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model {
    protected $fillable = ['purchase_id', 'product_id', 'quantity', 'unit_cost', 'subtotal'];
    public function purchase() { return $this->belongsTo(Purchase::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
PHP;

// ------------------------------------------------------------------------------
// 5. CONTROLLERS
// ------------------------------------------------------------------------------

// DashboardController
$files['app/Http/Controllers/DashboardController.php'] = <<<'PHP'
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $salesToday = Sale::whereDate('sold_at', today())->where('status', 'completed')->sum('total');
        $salesMonth = Sale::whereMonth('sold_at', now()->month)->where('status', 'completed')->sum('total');
        $totalTransactions = Sale::where('status', 'completed')->count();

        // Eager loading category untuk cegah N+1 query
        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->paginate(5)
            ->withQueryString();

        return view('dashboard', compact('totalProducts', 'salesToday', 'salesMonth', 'totalTransactions', 'lowStockProducts'));
    }
}
PHP;

// CategoryController
$files['app/Http/Controllers/CategoryController.php'] = <<<'PHP'
<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100|unique:categories,name']);
        Category::create($request->only('name'));
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string|max:100|unique:categories,name,' . $category->id]);
        $category->update($request->only('name'));
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->withErrors(['error' => 'Kategori tidak dapat dihapus karena memiliki produk terkait!']);
        }
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
PHP;

// SupplierController
$files['app/Http/Controllers/SupplierController.php'] = <<<'PHP'
<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string'
        ]);
        Supplier::create($request->all());
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan!');
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string'
        ]);
        $supplier->update($request->all());
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diperbarui!');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->purchases()->count() > 0) {
            return back()->withErrors(['error' => 'Supplier tidak dapat dihapus karena memiliki riwayat pembelian!']);
        }
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus!');
    }
}
PHP;

// ProductController
$files['app/Http/Controllers/ProductController.php'] = <<<'PHP'
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $products = Product::with('category')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
            })
            ->when($request->category_id, function ($query, $catId) {
                $query->where('category_id', $catId);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|string|max:50|unique:products,sku',
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'minimum_stock' => 'required|integer|min:0',
        ]);

        Product::create($request->all());
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'minimum_stock' => 'required|integer|min:0',
        ]);

        $product->update($request->all());
        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        // Gunakan Soft Delete agar riwayat transaksi tidak rusak
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil di-soft delete!');
    }
}
PHP;

// UserController
$files['app/Http/Controllers/UserController.php'] = <<<'PHP'
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,staff'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }
}
PHP;

// SaleController
$files['app/Http/Controllers/SaleController.php'] = <<<'PHP'
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
            ->when($request->search, function ($query, $search) {
                $query->where('sale_number', 'like', "%{$search}%");
            })
            ->when($request->date, function ($query, $date) {
                $query->whereDate('sold_at', $date);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

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
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $sale = DB::transaction(function () use ($request) {
                $sale = Sale::create([
                    'user_id' => auth()->id(),
                    'sale_number' => 'SL-' . date('YmdHis') . '-' . rand(100, 999),
                    'total' => 0,
                    'status' => 'completed',
                    'sold_at' => now(),
                ]);

                $grandTotal = 0;

                foreach ($request->items as $itemData) {
                    // Lock DB row untuk cegah Race Condition
                    $product = Product::lockForUpdate()->findOrFail($itemData['product_id']);
                    $qty = (int) $itemData['quantity'];

                    if ($product->stock < $qty) {
                        throw ValidationException::withMessages([
                            'items' => "Stok produk '{$product->name}' tidak mencukupi (Tersisa: {$product->stock}).",
                        ]);
                    }

                    // Ambil harga dari Server DB (Bukan browser)
                    $unitPrice = $product->selling_price;
                    $subtotal = $unitPrice * $qty;

                    $sale->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
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
        if ($sale->status === 'cancelled') {
            return back()->withErrors(['error' => 'Transaksi sudah dibatalkan sebelumnya.']);
        }

        try {
            DB::transaction(function () use ($sale) {
                foreach ($sale->items as $item) {
                    $product = Product::lockForUpdate()->find($item->product_id);
                    if ($product) {
                        $product->increment('stock', $item->quantity);
                    }
                }
                $sale->update(['status' => 'cancelled']);
            });

            return back()->with('success', 'Transaksi berhasil dibatalkan dan stok dikembalikan.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
PHP;

// PurchaseController
$files['app/Http/Controllers/PurchaseController.php'] = <<<'PHP'
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
            ->when($request->search, function ($query, $search) {
                $query->where('purchase_number', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        try {
            $purchase = DB::transaction(function () use ($request) {
                $purchase = Purchase::create([
                    'supplier_id' => $request->supplier_id,
                    'user_id' => auth()->id(),
                    'purchase_number' => 'PUR-' . date('YmdHis') . '-' . rand(100, 999),
                    'total' => 0,
                    'status' => 'completed',
                    'purchased_at' => now(),
                ]);

                $grandTotal = 0;

                foreach ($request->items as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                    $qty = (int) $item['quantity'];
                    $unitCost = (float) $item['unit_cost'];
                    $subtotal = $unitCost * $qty;

                    $purchase->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'unit_cost' => $unitCost,
                        'subtotal' => $subtotal,
                    ]);

                    // Stok bertambah (Stock-in)
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
PHP;

// ------------------------------------------------------------------------------
// 6. SEEDER (database/seeders/DatabaseSeeder.php)
// ------------------------------------------------------------------------------
$files['database/seeders/DatabaseSeeder.php'] = <<<'PHP'
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Utama
        User::create([
            'name' => 'Administrator Owner',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Staff Kasir 1',
            'email' => 'staff@mail.com',
            'password' => Hash::make('password123'),
            'role' => 'staff'
        ]);

        // Kategori
        $c1 = Category::create(['name' => 'Elektronik']);
        $c2 = Category::create(['name' => 'Aksesoris Komputer']);
        $c3 = Category::create(['name' => 'Peralatan Kantor']);

        // Supplier
        $s1 = Supplier::create(['name' => 'PT Utama Sembada', 'phone' => '08123456789', 'email' => 'supplier@mail.com', 'address' => 'Jakarta']);
        $s2 = Supplier::create(['name' => 'CV Mega Distribusi', 'phone' => '08987654321', 'email' => 'mega@mail.com', 'address' => 'Bandung']);

        // Produk Awal
        Product::create([
            'category_id' => $c1->id,
            'sku' => 'PRD-ELEK-001',
            'name' => 'Monitor LED 24 Inch',
            'purchase_price' => 1200000,
            'selling_price' => 1500000,
            'stock' => 15,
            'minimum_stock' => 5
        ]);

        Product::create([
            'category_id' => $c2->id,
            'sku' => 'PRD-AKS-002',
            'name' => 'Keyboard Mechanical RGB',
            'purchase_price' => 300000,
            'selling_price' => 450000,
            'stock' => 3, // Kena stok rendah
            'minimum_stock' => 5
        ]);

        Product::create([
            'category_id' => $c2->id,
            'sku' => 'PRD-AKS-003',
            'name' => 'Mouse Wireless Silent',
            'purchase_price' => 80000,
            'selling_price' => 130000,
            'stock' => 20,
            'minimum_stock' => 5
        ]);
    }
}
PHP;

// ------------------------------------------------------------------------------
// 7. BLADE VIEWS LENGKAP
// ------------------------------------------------------------------------------

// Layout utama
$files['resources/views/layouts/app.blade.php'] = <<<'PHP'
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaraInventory POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans text-slate-800">
    <nav class="bg-emerald-800 text-white shadow">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex space-x-6 items-center">
                <span class="font-bold text-lg">LaraInventory</span>
                <a href="{{ route('dashboard') }}" class="hover:text-emerald-200">Dashboard</a>
                <a href="{{ route('products.index') }}" class="hover:text-emerald-200">Produk</a>
                @can('admin-only')
                <a href="{{ route('categories.index') }}" class="hover:text-emerald-200">Kategori</a>
                <a href="{{ route('suppliers.index') }}" class="hover:text-emerald-200">Supplier</a>
                <a href="{{ route('users.index') }}" class="hover:text-emerald-200">Pengguna</a>
                @endcan
                <a href="{{ route('sales.index') }}" class="hover:text-emerald-200">Penjualan</a>
                <a href="{{ route('purchases.index') }}" class="hover:text-emerald-200">Pembelian</a>
                <a href="{{ route('sales.create') }}" class="bg-emerald-600 hover:bg-emerald-700 px-3 py-1.5 rounded text-sm font-semibold">+ Kasir POS</a>
            </div>
            <div class="flex items-center space-x-4 text-sm">
                <span class="bg-emerald-900 px-3 py-1 rounded-full text-emerald-200 border border-emerald-700">
                    {{ auth()->user()->name ?? 'Demo' }} ({{ strtoupper(auth()->user()->role ?? 'ADMIN') }})
                </span>
            </div>
        </div>
    </nav>
    <main class="max-w-7xl mx-auto py-8 px-4">
        @if(session('success'))
            <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded">
                {{ $errors->first() }}
            </div>
        @endif
        @yield('content')
    </main>
</body>
</html>
PHP;

// Dashboard
$files['resources/views/dashboard.blade.php'] = <<<'PHP'
@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-bold mb-6">Dashboard Ringkasan</h1>
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg border shadow-sm">
        <div class="text-sm text-slate-500 font-semibold">Total Produk</div>
        <div class="text-3xl font-bold text-emerald-700 mt-2">{{ $totalProducts }}</div>
    </div>
    <div class="bg-white p-6 rounded-lg border shadow-sm">
        <div class="text-sm text-slate-500 font-semibold">Penjualan Hari Ini</div>
        <div class="text-2xl font-bold text-emerald-700 mt-2">Rp {{ number_format($salesToday, 0, ',', '.') }}</div>
    </div>
    <div class="bg-white p-6 rounded-lg border shadow-sm">
        <div class="text-sm text-slate-500 font-semibold">Penjualan Bulan Ini</div>
        <div class="text-2xl font-bold text-emerald-700 mt-2">Rp {{ number_format($salesMonth, 0, ',', '.') }}</div>
    </div>
    <div class="bg-white p-6 rounded-lg border shadow-sm">
        <div class="text-sm text-slate-500 font-semibold">Total Transaksi</div>
        <div class="text-3xl font-bold text-emerald-700 mt-2">{{ $totalTransactions }}</div>
    </div>
</div>

<div class="bg-white rounded-lg border shadow-sm p-6">
    <h2 class="text-lg font-bold mb-4 text-red-600">Daftar Stok Rendah (≤ Minimum Stock)</h2>
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b bg-slate-50">
                <th class="p-3">SKU</th>
                <th class="p-3">Nama Produk</th>
                <th class="p-3">Kategori</th>
                <th class="p-3">Sisa Stok</th>
                <th class="p-3">Batas Min. Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lowStockProducts as $p)
            <tr class="border-b">
                <td class="p-3 font-mono text-sm">{{ $p->sku }}</td>
                <td class="p-3 font-semibold">{{ $p->name }}</td>
                <td class="p-3">{{ $p->category->name }}</td>
                <td class="p-3 text-red-600 font-bold">{{ $p->stock }}</td>
                <td class="p-3">{{ $p->minimum_stock }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="p-4 text-center text-slate-500">Stok seluruh produk masih mencukupi!</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $lowStockProducts->links() }}</div>
</div>
@endsection
PHP;

// Categories Index
$files['resources/views/categories/index.blade.php'] = <<<'PHP'
@extends('layouts.app')
@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-lg border shadow-sm">
        <h2 class="text-lg font-bold mb-4">Tambah Kategori</h2>
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Nama Kategori</label>
                <input type="text" name="name" class="w-full border p-2 rounded" required>
            </div>
            <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded font-semibold w-full">Simpan Kategori</button>
        </form>
    </div>
    <div class="md:col-span-2 bg-white p-6 rounded-lg border shadow-sm">
        <h2 class="text-lg font-bold mb-4">Daftar Kategori</h2>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b bg-slate-50">
                    <th class="p-3">ID</th>
                    <th class="p-3">Nama Kategori</th>
                    <th class="p-3">Jumlah Produk</th>
                    <th class="p-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $c)
                <tr class="border-b">
                    <td class="p-3">{{ $c->id }}</td>
                    <td class="p-3 font-semibold">{{ $c->name }}</td>
                    <td class="p-3">{{ $c->products_count }} Produk</td>
                    <td class="p-3">
                        <form action="{{ route('categories.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 font-semibold text-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $categories->links() }}</div>
    </div>
</div>
@endsection
PHP;

// Suppliers Index
$files['resources/views/suppliers/index.blade.php'] = <<<'PHP'
@extends('layouts.app')
@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-lg border shadow-sm">
        <h2 class="text-lg font-bold mb-4">Tambah Supplier</h2>
        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-semibold mb-1">Nama Supplier</label>
                <input type="text" name="name" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-semibold mb-1">No. Telepon</label>
                <input type="text" name="phone" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-semibold mb-1">Email</label>
                <input type="email" name="email" class="w-full border p-2 rounded">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-semibold mb-1">Alamat</label>
                <textarea name="address" class="w-full border p-2 rounded" rows="2"></textarea>
            </div>
            <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded font-semibold w-full">Simpan Supplier</button>
        </form>
    </div>
    <div class="md:col-span-2 bg-white p-6 rounded-lg border shadow-sm">
        <h2 class="text-lg font-bold mb-4">Daftar Supplier</h2>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b bg-slate-50">
                    <th class="p-3">Nama</th>
                    <th class="p-3">Kontak</th>
                    <th class="p-3">Alamat</th>
                    <th class="p-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $s)
                <tr class="border-b">
                    <td class="p-3 font-semibold">{{ $s->name }}</td>
                    <td class="p-3 text-sm">{{ $s->phone }}<br><span class="text-slate-400">{{ $s->email }}</span></td>
                    <td class="p-3 text-sm">{{ $s->address ?? '-' }}</td>
                    <td class="p-3">
                        <form action="{{ route('suppliers.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus supplier ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 font-semibold text-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $suppliers->links() }}</div>
    </div>
</div>
@endsection
PHP;

// Products Index
$files['resources/views/products/index.blade.php'] = <<<'PHP'
@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Daftar Produk Master</h1>
    @can('admin-only')
    <a href="{{ route('products.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded font-semibold">+ Tambah Produk</a>
    @endcan
</div>

<div class="bg-white rounded-lg border shadow-sm p-6">
    <form method="GET" action="{{ route('products.index') }}" class="flex space-x-4 mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / SKU..." class="border p-2 rounded w-1/3">
        <select name="category_id" class="border p-2 rounded w-1/4">
            <option value="">-- Semua Kategori --</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-slate-700 text-white px-4 py-2 rounded">Filter</button>
    </form>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b bg-slate-50">
                <th class="p-3">SKU</th>
                <th class="p-3">Nama Produk</th>
                <th class="p-3">Kategori</th>
                <th class="p-3">Harga Beli</th>
                <th class="p-3">Harga Jual</th>
                <th class="p-3">Stok</th>
                @can('admin-only')<th class="p-3">Aksi</th>@endcan
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
            <tr class="border-b">
                <td class="p-3 font-mono text-sm">{{ $p->sku }}</td>
                <td class="p-3 font-semibold">{{ $p->name }}</td>
                <td class="p-3">{{ $p->category->name }}</td>
                <td class="p-3">Rp {{ number_format($p->purchase_price, 0, ',', '.') }}</td>
                <td class="p-3 font-semibold text-emerald-700">Rp {{ number_format($p->selling_price, 0, ',', '.') }}</td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded text-xs font-bold {{ $p->stock <= $p->minimum_stock ? 'bg-red-100 text-red-800' : 'bg-emerald-100 text-emerald-800' }}">
                        {{ $p->stock }} Unit
                    </span>
                </td>
                @can('admin-only')
                <td class="p-3 space-x-2">
                    <a href="{{ route('products.edit', $p->id) }}" class="text-blue-600 font-semibold text-sm">Edit</a>
                    <form action="{{ route('products.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Soft delete produk ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 font-semibold text-sm">Hapus</button>
                    </form>
                </td>
                @endcan
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection
PHP;

// Products Create
$files['resources/views/products/create.blade.php'] = <<<'PHP'
@extends('layouts.app')
@section('content')
<div class="bg-white p-6 rounded-lg border shadow-sm max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-6">Tambah Produk Baru</h2>
    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold mb-1">SKU Unik</label>
                <input type="text" name="sku" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Kategori</label>
                <select name="category_id" class="w-full border p-2 rounded" required>
                    <option value="">-- Pilih --</option>
                    @foreach($categories as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Nama Produk</label>
            <input type="text" name="name" class="w-full border p-2 rounded" required>
        </div>
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-sm font-semibold mb-1">Harga Beli</label>
                <input type="number" name="purchase_price" min="0" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Harga Jual</label>
                <input type="number" name="selling_price" min="0" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Minimum Stok</label>
                <input type="number" name="minimum_stock" min="0" value="5" class="w-full border p-2 rounded" required>
            </div>
        </div>
        <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded font-semibold w-full">Simpan Produk</button>
    </form>
</div>
@endsection
PHP;

// Products Edit
$files['resources/views/products/edit.blade.php'] = <<<'PHP'
@extends('layouts.app')
@section('content')
<div class="bg-white p-6 rounded-lg border shadow-sm max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-6">Edit Produk</h2>
    <form action="{{ route('products.update', $product->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold mb-1">SKU Unik</label>
                <input type="text" name="sku" value="{{ $product->sku }}" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Kategori</label>
                <select name="category_id" class="w-full border p-2 rounded" required>
                    @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ $product->category_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Nama Produk</label>
            <input type="text" name="name" value="{{ $product->name }}" class="w-full border p-2 rounded" required>
        </div>
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-sm font-semibold mb-1">Harga Beli</label>
                <input type="number" name="purchase_price" value="{{ $product->purchase_price }}" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Harga Jual</label>
                <input type="number" name="selling_price" value="{{ $product->selling_price }}" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Minimum Stok</label>
                <input type="number" name="minimum_stock" value="{{ $product->minimum_stock }}" class="w-full border p-2 rounded" required>
            </div>
        </div>
        <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded font-semibold w-full">Update Produk</button>
    </form>
</div>
@endsection
PHP;

// Users Index
$files['resources/views/users/index.blade.php'] = <<<'PHP'
@extends('layouts.app')
@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-lg border shadow-sm">
        <h2 class="text-lg font-bold mb-4">Tambah Pengguna</h2>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-semibold mb-1">Nama Lengkap</label>
                <input type="text" name="name" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-semibold mb-1">Email</label>
                <input type="email" name="email" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-semibold mb-1">Password</label>
                <input type="password" name="password" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Role Akses</label>
                <select name="role" class="w-full border p-2 rounded" required>
                    <option value="staff">Staff</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded font-semibold w-full">Simpan Pengguna</button>
        </form>
    </div>
    <div class="md:col-span-2 bg-white p-6 rounded-lg border shadow-sm">
        <h2 class="text-lg font-bold mb-4">Daftar Pengguna Sistem</h2>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b bg-slate-50">
                    <th class="p-3">Nama</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr class="border-b">
                    <td class="p-3 font-semibold">{{ $u->name }}</td>
                    <td class="p-3">{{ $u->email }}</td>
                    <td class="p-3">
                        <span class="px-2 py-1 text-xs rounded font-bold {{ $u->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ strtoupper($u->role) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $users->links() }}</div>
    </div>
</div>
@endsection
PHP;

// Sales Create (POS)
$files['resources/views/sales/create.blade.php'] = <<<'PHP'
@extends('layouts.app')
@section('content')
<div class="bg-white p-6 rounded-lg border shadow-sm max-w-3xl mx-auto">
    <h2 class="text-xl font-bold mb-6">Form Transaksi Penjualan (POS)</h2>
    <form action="{{ route('sales.store') }}" method="POST">
        @csrf
        <div id="items-container" class="space-y-4 mb-6">
            <div class="flex space-x-3 item-row">
                <select name="items[0][product_id]" class="w-2/3 border p-2 rounded" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $p)
                    <option value="{{ $p->id }}">{{ $p->name }} (SKU: {{ $p->sku }}) - Stok: {{ $p->stock }} - Rp {{ number_format($p->selling_price, 0, ',', '.') }}</option>
                    @endforeach
                </select>
                <input type="number" name="items[0][quantity]" min="1" placeholder="Qty" class="w-1/3 border p-2 rounded" required>
            </div>
        </div>
        <div class="flex justify-between border-t pt-4">
            <button type="button" id="add-row" class="bg-slate-200 px-4 py-2 rounded text-sm">+ Tambah Item</button>
            <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded font-semibold">Proses Penjualan</button>
        </div>
    </form>
</div>
<script>
    let idx = 1;
    document.getElementById('add-row').addEventListener('click', function() {
        const container = document.getElementById('items-container');
        const row = container.querySelector('.item-row').cloneNode(true);
        row.querySelector('select').name = `items[${idx}][product_id]`;
        row.querySelector('select').value = '';
        row.querySelector('input').name = `items[${idx}][quantity]`;
        row.querySelector('input').value = '';
        container.appendChild(row);
        idx++;
    });
</script>
@endsection
PHP;

// Sales Index
$files['resources/views/sales/index.blade.php'] = <<<'PHP'
@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Riwayat Penjualan (Stock-Out)</h1>
    <a href="{{ route('sales.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded font-semibold">+ Transaksi Baru</a>
</div>

<div class="bg-white rounded-lg border shadow-sm p-6">
    <form method="GET" action="{{ route('sales.index') }}" class="flex space-x-4 mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="No. Transaksi..." class="border p-2 rounded w-1/3">
        <input type="date" name="date" value="{{ request('date') }}" class="border p-2 rounded">
        <button type="submit" class="bg-slate-700 text-white px-4 py-2 rounded">Filter</button>
    </form>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b bg-slate-50">
                <th class="p-3">No. Transaksi</th>
                <th class="p-3">Kasir</th>
                <th class="p-3">Total</th>
                <th class="p-3">Status</th>
                <th class="p-3">Waktu</th>
                <th class="p-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as$s)
            <tr class="border-b">
                <td class="p-3 font-mono text-sm">{{ $s->sale_number }}</td>
                <td class="p-3">{{ $s->user->name }}</td>
                <td class="p-3 font-semibold">Rp {{ number_format($s->total, 0, ',', '.') }}</td>
                <td class="p-3">
                    <span class="px-2 py-1 text-xs rounded font-bold {{ $s->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                        {{ strtoupper($s->status) }}
                    </span>
                </td>
                <td class="p-3 text-sm text-slate-500">{{ $s->sold_at }}</td>
                <td class="p-3 space-x-2">
                    <a href="{{ route('sales.show', $s->id) }}" class="text-blue-600 font-semibold text-sm">Detail</a>
                    @if($s->status === 'completed')
                    <form action="{{ route('sales.cancel', $s->id) }}" method="POST" class="inline" onsubmit="return confirm('Batalkan transaksi ini dan kembalikan stok?')">
                        @csrf
                        <button type="submit" class="text-red-600 font-semibold text-sm">Batalkan</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $sales->links() }}</div>
</div>
@endsection
PHP;

// Sales Show (Invoice)
$files['resources/views/sales/show.blade.php'] = <<<'PHP'
@extends('layouts.app')
@section('content')
<div class="bg-white p-6 rounded-lg border shadow-sm max-w-2xl mx-auto">
    <div class="flex justify-between items-center border-b pb-4 mb-4">
        <div>
            <h2 class="text-xl font-bold">Struk Penjualan</h2>
            <div class="text-sm font-mono text-slate-500">{{ $sale->sale_number }}</div>
        </div>
        <div class="text-right">
            <div class="text-sm">Kasir: {{ $sale->user->name }}</div>
            <div class="text-sm text-slate-500">{{ $sale->sold_at }}</div>
        </div>
    </div>
    <table class="w-full text-left border-collapse mb-6">
        <thead>
            <tr class="border-b bg-slate-50">
                <th class="p-2">Produk</th>
                <th class="p-2 text-center">Qty</th>
                <th class="p-2 text-right">Harga</th>
                <th class="p-2 text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as$item)
            <tr class="border-b">
                <td class="p-2 font-semibold">{{ $item->product->name }}</td>
                <td class="p-2 text-center">{{ $item->quantity }}</td>
                <td class="p-2 text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                <td class="p-2 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-right text-lg font-bold border-t pt-4">
        Total Akhir: <span class="text-emerald-700">Rp {{ number_format($sale->total, 0, ',', '.') }}</span>
    </div>
</div>
@endsection
PHP;

// Purchases Index
$files['resources/views/purchases/index.blade.php'] = <<<'PHP'
@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Riwayat Pembelian Stok (Stock-In)</h1>
    <a href="{{ route('purchases.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded font-semibold">+ Pembelian Baru</a>
</div>

<div class="bg-white rounded-lg border shadow-sm p-6">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b bg-slate-50">
                <th class="p-3">No. Transaksi</th>
                <th class="p-3">Supplier</th>
                <th class="p-3">Petugas</th>
                <th class="p-3">Total Total</th>
                <th class="p-3">Waktu</th>
                <th class="p-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchases as$p)
            <tr class="border-b">
                <td class="p-3 font-mono text-sm">{{ $p->purchase_number }}</td>
                <td class="p-3 font-semibold">{{ $p->supplier->name }}</td>
                <td class="p-3">{{ $p->user->name }}</td>
                <td class="p-3 font-semibold">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                <td class="p-3 text-sm text-slate-500">{{ $p->purchased_at }}</td>
                <td class="p-3">
                    <a href="{{ route('purchases.show', $p->id) }}" class="text-blue-600 font-semibold text-sm">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $purchases->links() }}</div>
</div>
@endsection
PHP;

// Purchases Create
$files['resources/views/purchases/create.blade.php'] = <<<'PHP'
@extends('layouts.app')
@section('content')
<div class="bg-white p-6 rounded-lg border shadow-sm max-w-3xl mx-auto">
    <h2 class="text-xl font-bold mb-6">Form Pembelian Stok Masuk</h2>
    <form action="{{ route('purchases.store') }}" method="POST">
        @csrf
        <div class="mb-6">
            <label class="block text-sm font-semibold mb-1">Pilih Supplier</label>
            <select name="supplier_id" class="w-full border p-2 rounded" required>
                <option value="">-- Pilih Supplier --</option>
                @foreach($suppliers as$sup)
                <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                @endforeach
            </select>
        </div>

        <div id="items-container" class="space-y-4 mb-6">
            <div class="flex space-x-3 item-row">
                <select name="items[0][product_id]" class="w-1/2 border p-2 rounded" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as$p)
                    <option value="{{ $p->id }}">{{ $p->name }} (Stok: {{$p->stock }})</option>
                    @endforeach
                </select>
                <input type="number" name="items[0][quantity]" min="1" placeholder="Qty" class="w-1/4 border p-2 rounded" required>
                <input type="number" name="items[0][unit_cost]" min="0" placeholder="Harga Beli Satuan" class="w-1/4 border p-2 rounded" required>
            </div>
        </div>
        <div class="flex justify-between border-t pt-4">
            <button type="button" id="add-row" class="bg-slate-200 px-4 py-2 rounded text-sm">+ Tambah Item</button>
            <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded font-semibold">Proses Stok Masuk</button>
        </div>
    </form>
</div>
<script>
    let idx = 1;
    document.getElementById('add-row').addEventListener('click', function() {
        const container = document.getElementById('items-container');
        const row = container.querySelector('.item-row').cloneNode(true);
        row.querySelector('select').name = `items[${idx}][product_id]`;
        row.querySelector('select').value = '';
        row.querySelectorAll('input')[0].name = `items[${idx}][quantity]`;
        row.querySelectorAll('input')[0].value = '';
        row.querySelectorAll('input')[1].name = `items[${idx}][unit_cost]`;
        row.querySelectorAll('input')[1].value = '';
        container.appendChild(row);
        idx++;
    });
</script>
@endsection
PHP;

// Purchases Show
$files['resources/views/purchases/show.blade.php'] = <<<'PHP'
@extends('layouts.app')
@section('content')
<div class="bg-white p-6 rounded-lg border shadow-sm max-w-2xl mx-auto">
    <div class="flex justify-between items-center border-b pb-4 mb-4">
        <div>
            <h2 class="text-xl font-bold">Detail Pembelian Stok</h2>
            <div class="text-sm font-mono text-slate-500">{{ $purchase->purchase_number }}</div>
        </div>
        <div class="text-right">
            <div class="text-sm font-semibold">Supplier: {{ $purchase->supplier->name }}</div>
            <div class="text-sm text-slate-500">{{ $purchase->purchased_at }}</div>
        </div>
    </div>
    <table class="w-full text-left border-collapse mb-6">
        <thead>
            <tr class="border-b bg-slate-50">
                <th class="p-2">Produk</th>
                <th class="p-2 text-center">Qty</th>
                <th class="p-2 text-right">Harga Beli</th>
                <th class="p-2 text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchase->items as$item)
            <tr class="border-b">
                <td class="p-2 font-semibold">{{ $item->product->name }}</td>
                <td class="p-2 text-center">{{ $item->quantity }}</td>
                <td class="p-2 text-right">Rp {{ number_format($item->unit_cost, 0, ',', '.') }}</td>
                <td class="p-2 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-right text-lg font-bold border-t pt-4">
        Total Pembelian: <span class="text-emerald-700">Rp {{ number_format($purchase->total, 0, ',', '.') }}</span>
    </div>
</div>
@endsection
PHP;

/