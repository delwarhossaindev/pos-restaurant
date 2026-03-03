<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::getValues();
        $users = User::orderBy('name')->get();
        return view('settings.index', compact('settings', 'users'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'restaurant_name' => 'required|string|max:200',
            'restaurant_phone' => 'nullable|string',
            'restaurant_address' => 'nullable|string',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'currency' => 'nullable|string|max:5',
            'receipt_footer' => 'nullable|string',
        ]);

        $fields = [
            'restaurant_name', 'restaurant_phone', 'restaurant_address',
            'restaurant_email', 'tax_rate', 'currency', 'receipt_footer',
            'receipt_header', 'logo'
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                Setting::setValue($field, $request->$field);
            }
        }

        Setting::clearCache();
        return back()->with('success', 'সেটিং সংরক্ষিত হয়েছে!');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,manager,cashier,waiter,kitchen',
            'phone' => 'nullable|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'স্টাফ যোগ হয়েছে!');
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'role' => 'required|in:admin,manager,cashier,waiter,kitchen',
        ]);

        $data = $request->only('name', 'email', 'phone', 'role');
        $data['is_active'] = $request->has('is_active');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return back()->with('success', 'স্টাফ আপডেট হয়েছে!');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'নিজেকে মুছতে পারবেন না।');
        }
        $user->delete();
        return back()->with('success', 'স্টাফ মুছে গেছে।');
    }
}
