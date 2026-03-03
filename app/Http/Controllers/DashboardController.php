<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\RestaurantTable;
use App\Models\MenuItem;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();

        $todaySales = Payment::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->sum('amount');

        $todayOrders = Order::whereDate('created_at', $today)
            ->whereNotIn('status', ['cancelled'])
            ->count();

        $activeOrders = Order::whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'served'])->count();

        $availableTables = RestaurantTable::where('status', 'available')->count();
        $occupiedTables = RestaurantTable::where('status', 'occupied')->count();
        $totalTables = RestaurantTable::count();

        $recentOrders = Order::with(['table', 'user', 'items'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $tables = RestaurantTable::with('activeOrder')->get();

        // Weekly sales for chart
        $weeklySales = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklySales[] = [
                'date' => $date->format('D'),
                'amount' => Payment::whereDate('created_at', $date)->where('status', 'completed')->sum('amount'),
            ];
        }

        // Top selling items
        $topItems = MenuItem::withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'todaySales', 'todayOrders', 'activeOrders',
            'availableTables', 'occupiedTables', 'totalTables',
            'recentOrders', 'tables', 'weeklySales', 'topItems'
        ));
    }
}
