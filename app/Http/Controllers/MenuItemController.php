<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)->get();
        $query = MenuItem::with('category');

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $items = $query->orderBy('category_id')->orderBy('sort_order')->paginate(15);
        return view('menu.items', compact('items', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'preparation_time' => 'nullable|integer|min:1',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('category_id', 'name', 'description', 'price', 'preparation_time', 'sort_order', 'is_available', 'is_featured');
        $data['is_available'] = $request->has('is_available');
        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menu-items', 'public');
        }

        MenuItem::create($data);
        return back()->with('success', 'মেনু আইটেম যোগ হয়েছে!');
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'price' => 'required|numeric|min:0',
        ]);

        $data = $request->only('category_id', 'name', 'description', 'price', 'preparation_time', 'sort_order');
        $data['is_available'] = $request->has('is_available');
        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image')) {
            if ($menuItem->image) {
                Storage::disk('public')->delete($menuItem->image);
            }
            $data['image'] = $request->file('image')->store('menu-items', 'public');
        }

        $menuItem->update($data);
        return back()->with('success', 'মেনু আইটেম আপডেট হয়েছে!');
    }

    public function destroy(MenuItem $menuItem)
    {
        if ($menuItem->image) {
            Storage::disk('public')->delete($menuItem->image);
        }
        $menuItem->delete();
        return back()->with('success', 'মেনু আইটেম মুছে গেছে!');
    }

    public function toggle(MenuItem $menuItem)
    {
        $menuItem->update(['is_available' => !$menuItem->is_available]);
        return back()->with('success', 'আইটেমের অবস্থা পরিবর্তন হয়েছে।');
    }
}
