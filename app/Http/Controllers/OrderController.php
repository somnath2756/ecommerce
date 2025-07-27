<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

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
        return view('orders.show', compact('order'));
    }
}
