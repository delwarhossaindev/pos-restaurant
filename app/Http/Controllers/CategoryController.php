<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('menuItems')->orderBy('sort_order')->get();
        return view('menu.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        Category::create($request->only('name', 'icon', 'color', 'sort_order', 'is_active'));
        return back()->with('success', 'ক্যাটাগরি যোগ হয়েছে!');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string|max:100']);
        $category->update($request->only('name', 'icon', 'color', 'sort_order', 'is_active'));
        return back()->with('success', 'ক্যাটাগরি আপডেট হয়েছে!');
    }

    public function destroy(Category $category)
    {
        if ($category->menuItems()->count() > 0) {
            return back()->with('error', 'এই ক্যাটাগরিতে আইটেম আছে, মুছতে পারবেন না।');
        }
        $category->delete();
        return back()->with('success', 'ক্যাটাগরি মুছে গেছে!');
    }

    public function toggle(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);
        return back()->with('success', 'ক্যাটাগরির অবস্থা পরিবর্তন হয়েছে।');
    }
}
