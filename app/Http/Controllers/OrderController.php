<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            $orders = Order::all(); // Admin sees all orders
        } else {
            $orders = auth()->user()->orders; // Buyer/Seller sees their own orders
        }
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Authorize: Only owner or admin/seller who sold items in this order can view
        // For simplicity, we'll allow buyer/admin to see their own orders for now
        if (!auth()->user()->hasRole('admin') && auth()->id() !== $order->user_id) {
            abort(403);
        }
        $order->load('user'); // Eager load the user relationship
        return view('orders.show', compact('order'));
    }

    public function destroy(Request $request, $orderId)
    {
        $user = Auth::user();
        $order = Order::findOrFail($orderId);

        if ($user->hasRole('admin') || ($user->hasRole('seller') && $order->items()->where('seller_id', $user->id)->exists())) {
            $order->update(['status' => 'cancelled_by_seller']);
            \Log::info("Order {$order->id} marked as cancelled_by_seller by user {$user->id} (role: {$user->roles->pluck('name')->implode(', ')})");
            return redirect()->route('orders.index')->with('success', 'Order canceled successfully.');
        }

        \Log::error("Unauthorized delete attempt by user {$user->id} for order {$order->id}");
        return redirect()->route('orders.index')->with('error', 'Unauthorized action.');
    }

    //uhn
    
    
}
