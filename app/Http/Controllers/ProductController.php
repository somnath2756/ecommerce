<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // Make sure to use the Auth facade
use App\Models\Cart; // Make sure to use the Cart model

class ProductController extends Controller
{
    /**
     * Display a listing of the products (for sellers to manage their own).
     * Or for admin to see all.
     */
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            $products = Product::all(); // Admin sees all products
        } else {
            $products = auth()->user()->products; // Seller sees only their products
        }
        
        // Get product IDs in the user's cart
        $cartProductIds = auth()->check() ? Cart::where('user_id', auth()->id())->first()?->items()->pluck('product_id')->toArray() ?? [] : [];
        return view('products.index', compact('products','cartProductIds'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
       
        $categories = Product::whereNotNull('category')->distinct()->pluck('category');
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');
        }

        auth()->user()->products()->create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath,
            
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product. (Optional: for individual product view)
     */
    public function show(Product $product)
    {
        
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        // Authorize: Only owner or admin can edit
        if (!auth()->user()->hasRole('admin') && auth()->id() !== $product->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Product::whereNotNull('category')->distinct()->pluck('category');
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Authorize: Only owner or admin can update
        if (!auth()->user()->hasRole('admin') && auth()->id() !== $product->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'nullable|string|max:255',
            'new_category' => 'nullable|string|max:255',
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('product_images', 'public');
        } elseif ($request->input('clear_image')) { // Handle checkbox to clear image
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = null;
        }
        // Use new_category if provided, otherwise use category
        $category = $request->filled('new_category') ? $request->new_category : $request->category;

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath,
            'category' => $category,
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Authorize: Only owner or admin can delete
        if (!auth()->user()->hasRole('admin') && auth()->id() !== $product->user_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
 
    public function allProducts()
    {
        $products = Product::where('stock', '>', 0)->get(); // Only show in-stock products
        return view('products.all', compact('products'));
    }
}