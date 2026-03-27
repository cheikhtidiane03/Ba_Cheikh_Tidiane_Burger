<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class CatalogController extends Controller
{
    public function index()
    {
        $categories = Category::with('activeProducts')->get();

        $products = Product::available()
                           ->with('category')
                           ->when(request('search'), fn($q) =>
                               $q->where('name', 'like', '%'.request('search').'%')
                           )
                           ->when(request('category'), fn($q) =>
                               $q->where('category_id', request('category'))
                           )
                           ->when(request('sort') === 'price_asc',  fn($q) => $q->orderBy('price'))
                           ->when(request('sort') === 'price_desc', fn($q) => $q->orderByDesc('price'))
                           ->paginate(9);

        return view('client.catalog.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        abort_if($product->is_archived, 404);
        return view('client.catalog.show', compact('product'));
    }
}