<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\RestaurantTable;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==================== Users ====================
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@pos.com',
            'phone' => '01700000001',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        User::create([
            'name' => 'ক্যাশিয়ার রহিম',
            'email' => 'cashier@pos.com',
            'phone' => '01700000003',
            'role' => 'cashier',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        User::create([
            'name' => 'কিচেন শেফ আলী',
            'email' => 'kitchen@pos.com',
            'phone' => '01700000004',
            'role' => 'kitchen',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        // ==================== Settings ====================
        $settingsData = [
            'restaurant_name' => 'মেঘনা রেস্টুরেন্ট',
            'restaurant_phone' => '01800-000000',
            'restaurant_address' => 'ঢাকা, বাংলাদেশ',
            'restaurant_email' => 'info@meghna.com',
            'tax_rate' => '5',
            'currency' => '৳',
            'receipt_footer' => 'আমাদের সেবা গ্রহণের জন্য আপনাকে ধন্যবাদ! আবার আসবেন।',
            'receipt_header' => '',
        ];

        foreach ($settingsData as $key => $value) {
            Setting::create(['key' => $key, 'value' => $value]);
        }

        // ==================== Categories ====================
        $categories = [
            ['name' => 'বার্গার', 'icon' => 'fas fa-hamburger', 'color' => '#FF6B35', 'sort_order' => 1],
            ['name' => 'পিৎজা', 'icon' => 'fas fa-pizza-slice', 'color' => '#e74c3c', 'sort_order' => 2],
            ['name' => 'চিকেন', 'icon' => 'fas fa-drumstick-bite', 'color' => '#f39c12', 'sort_order' => 3],
            ['name' => 'ভাত ও রুটি', 'icon' => 'fas fa-bread-slice', 'color' => '#8e44ad', 'sort_order' => 4],
            ['name' => 'সুপ ও স্যালাড', 'icon' => 'fas fa-leaf', 'color' => '#27ae60', 'sort_order' => 5],
            ['name' => 'পানীয়', 'icon' => 'fas fa-coffee', 'color' => '#2980b9', 'sort_order' => 6],
            ['name' => 'ডেজার্ট', 'icon' => 'fas fa-ice-cream', 'color' => '#e91e63', 'sort_order' => 7],
            ['name' => 'ফাস্টফুড', 'icon' => 'fas fa-hotdog', 'color' => '#795548', 'sort_order' => 8],
        ];

        $menuItemsMap = [
            'বার্গার' => [
                ['name' => 'চিকেন বার্গার', 'price' => 180, 'preparation_time' => 10, 'is_available' => true, 'is_featured' => true],
                ['name' => 'বিফ বার্গার', 'price' => 220, 'preparation_time' => 12, 'is_available' => true, 'is_featured' => false],
                ['name' => 'ভেজি বার্গার', 'price' => 150, 'preparation_time' => 8, 'is_available' => true, 'is_featured' => false],
                ['name' => 'ডাবল চিজ বার্গার', 'price' => 280, 'preparation_time' => 12, 'is_available' => true, 'is_featured' => true],
            ],
            'পিৎজা' => [
                ['name' => 'মার্গারিটা পিৎজা', 'price' => 320, 'preparation_time' => 20, 'is_available' => true, 'is_featured' => false],
                ['name' => 'চিকেন পিৎজা', 'price' => 380, 'preparation_time' => 20, 'is_available' => true, 'is_featured' => true],
                ['name' => 'বিবিকিউ পিৎজা', 'price' => 450, 'preparation_time' => 22, 'is_available' => true, 'is_featured' => true],
            ],
            'চিকেন' => [
                ['name' => 'ফ্রাইড চিকেন (২ পিস)', 'price' => 250, 'preparation_time' => 15, 'is_available' => true, 'is_featured' => true],
                ['name' => 'চিকেন গ্রিল', 'price' => 320, 'preparation_time' => 20, 'is_available' => true, 'is_featured' => false],
                ['name' => 'চিকেন শর্মা', 'price' => 200, 'preparation_time' => 10, 'is_available' => true, 'is_featured' => false],
                ['name' => 'চিকেন রোস্ট', 'price' => 400, 'preparation_time' => 25, 'is_available' => true, 'is_featured' => true],
            ],
            'ভাত ও রুটি' => [
                ['name' => 'ভাত (১ প্লেট)', 'price' => 50, 'preparation_time' => 5, 'is_available' => true, 'is_featured' => false],
                ['name' => 'বিরিয়ানি (চিকেন)', 'price' => 220, 'preparation_time' => 5, 'is_available' => true, 'is_featured' => true],
                ['name' => 'বিরিয়ানি (মাটন)', 'price' => 280, 'preparation_time' => 5, 'is_available' => true, 'is_featured' => false],
                ['name' => 'পরোটা (১টি)', 'price' => 30, 'preparation_time' => 5, 'is_available' => true, 'is_featured' => false],
                ['name' => 'নান রুটি', 'price' => 40, 'preparation_time' => 8, 'is_available' => true, 'is_featured' => false],
            ],
            'সুপ ও স্যালাড' => [
                ['name' => 'চিকেন সুপ', 'price' => 120, 'preparation_time' => 10, 'is_available' => true, 'is_featured' => false],
                ['name' => 'টমেটো সুপ', 'price' => 100, 'preparation_time' => 10, 'is_available' => true, 'is_featured' => false],
                ['name' => 'গার্ডেন স্যালাড', 'price' => 150, 'preparation_time' => 5, 'is_available' => true, 'is_featured' => false],
            ],
            'পানীয়' => [
                ['name' => 'কোকা কোলা', 'price' => 60, 'preparation_time' => 2, 'is_available' => true, 'is_featured' => false],
                ['name' => 'পেপসি', 'price' => 60, 'preparation_time' => 2, 'is_available' => true, 'is_featured' => false],
                ['name' => 'ফ্রেশ লেমনেড', 'price' => 80, 'preparation_time' => 5, 'is_available' => true, 'is_featured' => true],
                ['name' => 'লাচ্ছি', 'price' => 100, 'preparation_time' => 5, 'is_available' => true, 'is_featured' => true],
                ['name' => 'চা', 'price' => 30, 'preparation_time' => 5, 'is_available' => true, 'is_featured' => false],
                ['name' => 'কফি', 'price' => 80, 'preparation_time' => 5, 'is_available' => true, 'is_featured' => false],
                ['name' => 'মিনারেল ওয়াটার', 'price' => 30, 'preparation_time' => 1, 'is_available' => true, 'is_featured' => false],
            ],
            'ডেজার্ট' => [
                ['name' => 'আইসক্রিম (১ স্কুপ)', 'price' => 80, 'preparation_time' => 3, 'is_available' => true, 'is_featured' => false],
                ['name' => 'চকলেট কেক', 'price' => 150, 'preparation_time' => 2, 'is_available' => true, 'is_featured' => true],
                ['name' => 'ব্রাউনি', 'price' => 130, 'preparation_time' => 2, 'is_available' => true, 'is_featured' => false],
            ],
            'ফাস্টফুড' => [
                ['name' => 'ফ্রেঞ্চ ফ্রাইস', 'price' => 100, 'preparation_time' => 8, 'is_available' => true, 'is_featured' => false],
                ['name' => 'চিকেন নাগেটস (৬ পিস)', 'price' => 180, 'preparation_time' => 10, 'is_available' => true, 'is_featured' => true],
                ['name' => 'হটডগ', 'price' => 120, 'preparation_time' => 8, 'is_available' => true, 'is_featured' => false],
                ['name' => 'স্যান্ডউইচ', 'price' => 150, 'preparation_time' => 8, 'is_available' => true, 'is_featured' => false],
            ],
        ];

        foreach ($categories as $catData) {
            $cat = Category::create($catData);
            $items = $menuItemsMap[$catData['name']] ?? [];
            foreach ($items as $i => $item) {
                MenuItem::create(array_merge($item, [
                    'category_id' => $cat->id,
                    'sort_order' => $i + 1,
                ]));
            }
        }

        // ==================== Restaurant Tables ====================
        $tables = [
            ['table_number' => '1', 'capacity' => 2, 'location' => 'Main Hall'],
            ['table_number' => '2', 'capacity' => 4, 'location' => 'Main Hall'],
            ['table_number' => '3', 'capacity' => 4, 'location' => 'Main Hall'],
            ['table_number' => '4', 'capacity' => 6, 'location' => 'Main Hall'],
            ['table_number' => '5', 'capacity' => 6, 'location' => 'Main Hall'],
            ['table_number' => '6', 'capacity' => 4, 'location' => 'Main Hall'],
            ['table_number' => '7', 'capacity' => 2, 'location' => 'Main Hall'],
            ['table_number' => '8', 'capacity' => 8, 'location' => 'Main Hall'],
            ['table_number' => 'V1', 'capacity' => 4, 'location' => 'VIP'],
            ['table_number' => 'V2', 'capacity' => 6, 'location' => 'VIP'],
            ['table_number' => 'O1', 'capacity' => 4, 'location' => 'Outdoor'],
            ['table_number' => 'O2', 'capacity' => 4, 'location' => 'Outdoor'],
        ];

        foreach ($tables as $table) {
            RestaurantTable::create($table);
        }
    }
}
