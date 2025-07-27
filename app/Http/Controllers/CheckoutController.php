<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = auth()->user()->cart()->with('items.product')->firstOrCreate([]);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Basic check for stock before checkout (can be more robust)
        foreach ($cart->items as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('cart.index')->with('error', "Not enough stock for {$item->product->name}. Please adjust quantity.");
            }
        }

        return view('checkout.index', compact('cart'));
    }

    public function placeOrder(Request $request)
    {
        // In a real application, this would involve payment gateway interaction
        // For now, we simulate success and create the order

        $cart = auth()->user()->cart()->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            $orderItemsData = [];

            // Re-validate stock and calculate total
            foreach ($cart->items as $item) {
                if ($item->product->stock < $item->quantity) {
                    DB::rollBack();
                    return redirect()->route('cart.index')->with('error', "Insufficient stock for {$item->product->name}. Please update your cart.");
                }
                $totalAmount += $item->quantity * $item->product->price;
                $orderItemsData[] = [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price, // Record price at time of purchase
                ];

                // Decrease product stock
                $item->product->decrement('stock', $item->quantity);
            }

            $order = auth()->user()->orders()->create([
                'total_amount' => $totalAmount,
                'status' => 'completed', // Or 'pending_payment' depending on your flow
            ]);

            // Attach order items
            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);
            }

            // Clear the cart
            $cart->items()->delete();
            $cart->delete();

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Order placement failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to place order. Please try again.');
        }
    }
}
