<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.menuItem', 'table'])
            ->whereIn('status', ['confirmed', 'preparing'])
            ->orderBy('created_at')
            ->get();

        $pendingOrders = Order::with(['items.menuItem', 'table'])
            ->where('status', 'pending')
            ->orderBy('created_at')
            ->get();

        return view('kitchen.index', compact('orders', 'pendingOrders'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:confirmed,preparing,ready']);
        $order->update(['status' => $request->status]);
        return response()->json(['success' => true, 'status' => $order->status]);
    }

    public function updateItemStatus(Request $request, OrderItem $item)
    {
        $request->validate(['status' => 'required|in:pending,preparing,ready,served']);
        $item->update(['status' => $request->status]);

        // If all items ready, mark order as ready
        $order = $item->order;
        if ($order->items()->where('status', '!=', 'ready')->count() === 0) {
            $order->update(['status' => 'ready']);
        }

        return response()->json(['success' => true]);
    }

    public function getActiveOrders()
    {
        $orders = Order::with(['items.menuItem', 'table'])
            ->whereIn('status', ['pending', 'confirmed', 'preparing'])
            ->orderBy('created_at')
            ->get();

        return response()->json($orders);
    }
}
