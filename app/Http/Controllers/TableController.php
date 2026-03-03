<?php

namespace App\Http\Controllers;

use App\Models\RestaurantTable;
use App\Models\Order;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = RestaurantTable::with('activeOrder.items')->get();
        return view('tables.index', compact('tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string|unique:restaurant_tables',
            'capacity' => 'required|integer|min:1|max:50',
            'location' => 'nullable|string',
        ]);

        RestaurantTable::create($request->only('table_number', 'capacity', 'location'));
        return back()->with('success', 'টেবিল যোগ হয়েছে!');
    }

    public function update(Request $request, RestaurantTable $table)
    {
        $request->validate([
            'table_number' => 'required|string|unique:restaurant_tables,table_number,' . $table->id,
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string',
        ]);

        $table->update($request->only('table_number', 'capacity', 'location', 'status'));
        return back()->with('success', 'টেবিল আপডেট হয়েছে!');
    }

    public function destroy(RestaurantTable $table)
    {
        if ($table->orders()->whereNotIn('status', ['completed', 'cancelled'])->exists()) {
            return back()->with('error', 'এই টেবিলে সক্রিয় অর্ডার আছে।');
        }
        $table->delete();
        return back()->with('success', 'টেবিল মুছে গেছে!');
    }

    public function updateStatus(Request $request, RestaurantTable $table)
    {
        $table->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }
}
