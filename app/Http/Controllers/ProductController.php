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
            ->when($request->search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%");
            })
            ->when($request->category_id, function ($q, $catId) {
                $q->where('category_id', $catId);
            })
            ->latest()->paginate(10)->withQueryString();

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
            'sku'           => 'required|string|max:50|unique:products,sku',
            'name'          => 'required|string|max:150',
            'description'   => 'nullable|string',
            'category_id'   => 'required|exists:categories,id',
            'purchase_price'=> 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'minimum_stock' => 'required|integer|min:0',
        ], [
            'sku.required'            => 'Kode SKU unik wajib diisi.',
            'sku.unique'              => 'Kode SKU ini sudah digunakan oleh produk lain.',
            'name.required'           => 'Nama produk wajib diisi.',
            'category_id.required'    => 'Pilih kategori produk.',
            'purchase_price.required' => 'Harga beli wajib diisi.',
            'selling_price.required'  => 'Harga jual wajib diisi.',
            'minimum_stock.required'  => 'Batas minimum stok wajib diisi.',
        ]);

        Product::create($request->all());
        return redirect()->route('products.index')->with('success', "Produk '{$request->name}' berhasil ditambahkan ke inventaris!");
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'sku'           => 'required|string|max:50|unique:products,sku,' . $product->id,
            'name'          => 'required|string|max:150',
            'description'   => 'nullable|string',
            'category_id'   => 'required|exists:categories,id',
            'purchase_price'=> 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'minimum_stock' => 'required|integer|min:0',
        ]);

        $product->update($request->all());
        return redirect()->route('products.index')->with('success', "Produk '{$product->name}' berhasil diperbarui!");
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', "Produk '{$product->name}' berhasil di-soft delete!");
    }
}
