<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\MenuItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ? $request->start_date : now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ? $request->end_date : now()->format('Y-m-d');

        $totalSales = Payment::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status', 'completed')->sum('amount');

        $totalOrders = Order::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereNotIn('status', ['cancelled'])->count();

        $cancelledOrders = Order::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status', 'cancelled')->count();

        $avgOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Daily sales
        $dailySales = Payment::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status', 'completed')
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top items
        $topItems = DB::table('order_items')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereNotIn('orders.status', ['cancelled'])
            ->selectRaw('menu_items.name, SUM(order_items.quantity) as total_qty, SUM(order_items.total_price) as total_amount')
            ->groupBy('menu_items.id', 'menu_items.name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // Payment methods
        $paymentMethods = Payment::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status', 'completed')
            ->selectRaw('method, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('method')
            ->get();

        // Order types
        $orderTypes = Order::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereNotIn('status', ['cancelled'])
            ->selectRaw('order_type, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('order_type')
            ->get();

        return view('reports.index', compact(
            'totalSales', 'totalOrders', 'cancelledOrders', 'avgOrderValue',
            'dailySales', 'topItems', 'paymentMethods', 'orderTypes',
            'startDate', 'endDate'
        ));
    }
}
