<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RestaurantTable;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['table', 'user', 'items.menuItem', 'payment'])
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('orders.index', compact('orders'));
    }

    public function pos(Request $request)
    {
        $tables = RestaurantTable::where('status', 'available')
            ->orWhere('status', 'occupied')
            ->orderBy('table_number')
            ->get();
        $categories = Category::with('activeMenuItems')->where('is_active', true)->orderBy('sort_order')->get();
        $selectedTableId = $request->table_id;
        $activeOrder = null;

        if ($selectedTableId) {
            $table = RestaurantTable::findOrFail($selectedTableId);
            $activeOrder = $table->activeOrder()->with('items.menuItem')->first();
        }

        return view('orders.pos', compact('tables', 'categories', 'selectedTableId', 'activeOrder'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_type' => 'required|in:dine_in,takeaway,delivery',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $tableId = $request->restaurant_table_id;
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'restaurant_table_id' => $tableId,
                'user_id' => Auth::id(),
                'order_type' => $request->order_type,
                'status' => 'pending',
                'guests' => $request->guests ?? 1,
                'notes' => $request->notes,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'subtotal' => 0,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total_amount' => 0,
            ]);

            foreach ($request->items as $item) {
                $menuItem = MenuItem::findOrFail($item['menu_item_id']);
                $totalPrice = $menuItem->price * $item['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menuItem->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $menuItem->price,
                    'total_price' => $totalPrice,
                    'notes' => $item['notes'] ?? null,
                    'status' => 'pending',
                ]);
            }

            $order->load('items');
            $order->recalculateTotals();

            if ($tableId) {
                RestaurantTable::where('id', $tableId)->update(['status' => 'occupied']);
            }

            DB::commit();
            return response()->json(['success' => true, 'order_id' => $order->id, 'order_number' => $order->order_number]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show(Order $order)
    {
        $order->load(['table', 'user', 'items.menuItem', 'payment']);
        return view('orders.show', compact('order'));
    }

    public function addItem(Request $request, Order $order)
    {
        $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $menuItem = MenuItem::findOrFail($request->menu_item_id);
        $existing = $order->items()->where('menu_item_id', $menuItem->id)->first();

        if ($existing) {
            $existing->update([
                'quantity' => $existing->quantity + $request->quantity,
                'total_price' => ($existing->quantity + $request->quantity) * $existing->unit_price,
            ]);
        } else {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $menuItem->id,
                'quantity' => $request->quantity,
                'unit_price' => $menuItem->price,
                'total_price' => $menuItem->price * $request->quantity,
                'notes' => $request->notes,
                'status' => 'pending',
            ]);
        }

        $order->load('items');
        $order->recalculateTotals();

        return response()->json(['success' => true, 'total' => $order->total_amount]);
    }

    public function removeItem(Order $order, OrderItem $item)
    {
        $item->delete();
        $order->load('items');
        $order->recalculateTotals();
        return response()->json(['success' => true, 'total' => $order->total_amount]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,preparing,ready,served,completed,cancelled']);
        $order->update(['status' => $request->status]);

        if ($request->status === 'completed' || $request->status === 'cancelled') {
            if ($order->restaurant_table_id) {
                RestaurantTable::where('id', $order->restaurant_table_id)->update(['status' => 'available']);
            }
        }

        return response()->json(['success' => true]);
    }

    public function applyDiscount(Request $request, Order $order)
    {
        $request->validate(['discount' => 'required|numeric|min:0']);
        $order->update(['discount_amount' => $request->discount]);
        $order->load('items');
        $order->recalculateTotals();
        return response()->json(['success' => true, 'total' => $order->total_amount]);
    }

    public function destroy(Order $order)
    {
        if ($order->payment) {
            return back()->with('error', 'পেমেন্ট করা অর্ডার মুছতে পারবেন না।');
        }
        $tableId = $order->restaurant_table_id;
        $order->delete();
        if ($tableId) {
            RestaurantTable::where('id', $tableId)->update(['status' => 'available']);
        }
        return back()->with('success', 'অর্ডার মুছে গেছে।');
    }

    public function getOrderData(Order $order)
    {
        $order->load(['items.menuItem', 'table']);
        return response()->json($order);
    }
}
