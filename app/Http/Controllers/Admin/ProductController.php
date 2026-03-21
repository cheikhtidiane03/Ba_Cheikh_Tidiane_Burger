<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::notArchived()
                           ->with('category')
                           ->when(request('search'), fn($q) =>
                               $q->where('name', 'like', '%'.request('search').'%')
                           )
                           ->when(request('category'), fn($q) =>
                               $q->where('category_id', request('category'))
                           )
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);

        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:150|unique:products,name',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_available'=> 'boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name'         => $request->name,
            'category_id'  => $request->category_id,
            'description'  => $request->description,
            'price'        => $request->price,
            'stock'        => $request->stock,
            'image'        => $imagePath,
            'is_available' => $request->boolean('is_available', true),
        ]);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Burger "' . $request->name . '" créé avec succès !');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required|string|max:150|unique:products,name,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_available'=> 'boolean',
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name'         => $request->name,
            'category_id'  => $request->category_id,
            'description'  => $request->description,
            'price'        => $request->price,
            'stock'        => $request->stock,
            'image'        => $imagePath,
            'is_available' => $request->boolean('is_available', true),
        ]);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Burger "' . $product->name . '" modifié avec succès !');
    }

    public function destroy(Product $product)
    {
        // Supprimer l'image si elle existe
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('delete', 'Burger supprimé avec succès.');
    }

    /**
     * Archiver / désarchiver un produit
     */
    public function toggleArchive(Product $product)
    {
        $product->update(['is_archived' => !$product->is_archived]);

        $message = $product->is_archived
            ? 'Burger archivé — il n\'est plus visible dans le catalogue.'
            : 'Burger désarchivé — il est à nouveau visible.';

        return back()->with('success', $message);
    }
}