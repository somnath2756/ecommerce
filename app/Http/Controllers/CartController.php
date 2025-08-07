<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = auth()->user()->cart()->with('items.product')->firstOrCreate([]);
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($product->stock < $request->quantity) {
            // Check if it's an AJAX request to return JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock available for this product.',
                ], 400); // Bad Request status
            }
            return back()->with('error', 'Not enough stock available for this product.');
        }

        $user = auth()->user();
        $cart = $user->cart()->firstOrCreate([]);

        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            // Update quantity if item already in cart
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($product->stock < $newQuantity) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Adding this many items exceeds available stock.',
                    ], 400);
                }
                return back()->with('error', 'Adding this many items exceeds available stock.');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // Add new item to cart
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }

        // Calculate total cart items for the user's cart
        // Assuming your Cart model has a 'hasMany' relationship named 'items' to CartItem
        // And CartItem has a 'quantity' column
        $cartCount = $cart->items->sum('quantity');

        // Check if it's an AJAX request to return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart!',
                'cartCount' => $cartCount, // Send the updated cart count
            ]);
        }

        return back()->with('success', 'Product added to cart!');
    }

    public function contents()
    {
        $cart = auth()->check() ? Cart::where('user_id', auth()->id())->first() : null;
        $cartProductIds = $cart ? $cart->items()->pluck('product_id')->toArray() : [];
        return response()->json([
            'cartProductIds' => $cartProductIds,
        ]);
    }
    /**
     * Get the current cart count for the authenticated user.
     * This method will be called via AJAX on page load.
     * Ensure the user is authenticated before calling this.
     */
    public function getCartCount(Request $request)
{
    $cartCount = 0;
    if (auth()->check()) {
        $cart = Cart::where('user_id', auth()->id())->first();
        $cartCount = $cart ? $cart->items()->sum('quantity') : 0;
    }
    return response()->json(['cartCount' => $cartCount]);
}


    
    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Ensure the cart item belongs to the authenticated user's cart
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        if ($cartItem->product->stock < $request->quantity) {
            return back()->with('error', 'Not enough stock available for this product.');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    public function remove(CartItem $cartItem)
    {
        // Ensure the cart item belongs to the authenticated user's cart
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Product removed from cart.');
    }
}
