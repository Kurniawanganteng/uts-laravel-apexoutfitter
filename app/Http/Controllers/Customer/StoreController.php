<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        $products = Product::with('category')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->category_id, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($request->sort, function ($query, $sort) {
                if ($sort === 'price_asc') {
                    $query->orderBy('selling_price', 'asc');
                } elseif ($sort === 'price_desc') {
                    $query->orderBy('selling_price', 'desc');
                } elseif ($sort === 'name') {
                    $query->orderBy('name', 'asc');
                } else {
                    $query->latest();
                }
            }, function ($query) {
                $query->latest();
            })
            ->paginate(12)
            ->withQueryString();

        return view('customer.store.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load('category');
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('customer.store.show', compact('product', 'relatedProducts'));
    }
}
