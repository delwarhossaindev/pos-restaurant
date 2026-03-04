<div align="center">

# 🍽️ রেস্টুরেন্ট POS সিস্টেম

### বাংলাদেশের রেস্টুরেন্টগুলোর জন্য সম্পূর্ণ পয়েন্ট অফ সেল ম্যানেজমেন্ট সিস্টেম

![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-Database-003B57?style=for-the-badge&logo=sqlite&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

</div>

---

## 🚀 প্রযুক্তি স্ট্যাক

| প্রযুক্তি | ভার্সন | বিবরণ |
|-----------|--------|--------|
| 🐘 **PHP** | 8.3+ | সার্ভার-সাইড ল্যাঙ্গুয়েজ |
| 🔴 **Laravel** | 10.x | PHP ফ্রেমওয়ার্ক |
| 🗄️ **SQLite** | 3.x | ডেটাবেজ |
| 🎨 **Bootstrap** | 5 | CSS ফ্রেমওয়ার্ক |
| ⭐ **Font Awesome** | 6 | আইকন লাইব্রেরি |
| 📊 **Chart.js** | Latest | গ্রাফ ও চার্ট |
| 🔤 **Hind Siliguri** | — | বাংলা ফন্ট |

---

## ✨ সিস্টেমের ফিচারসমূহ

| # | মডিউল | বিবরণ |
|---|-------|--------|
| 📊 | **ড্যাশবোর্ড** | আজকের বিক্রয়, অর্ডার স্ট্যাটাস, টেবিল গ্রিড, সাপ্তাহিক চার্ট |
| 🛒 | **POS অর্ডার** | ক্যাটাগরি ফিল্টার, কার্ট, পেমেন্ট মডাল, ডিসকাউন্ট |
| 👨‍🍳 | **কিচেন ডিসপ্লে (KDS)** | রিয়েল-টাইম অর্ডার ট্র্যাকিং, ডার্ক থিম, টাইমার |
| 🪑 | **টেবিল ম্যানেজমেন্ট** | ফ্লোর প্ল্যান, স্ট্যাটাস আপডেট (খালি/ব্যস্ত/রিজার্ভ) |
| 🍔 | **মেনু ম্যানেজমেন্ট** | ক্যাটাগরি ও আইটেম CRUD, ছবি আপলোড |
| 🧾 | **বিলিং ও রিসিট** | ইনভয়েস প্রিন্ট, থার্মাল রিসিট, পেমেন্ট মেথড |
| 📈 | **রিপোর্ট** | দৈনিক/সাপ্তাহিক বিক্রয়, টপ আইটেম, পেমেন্ট বিশ্লেষণ |
| ⚙️ | **সেটিংস** | রেস্টুরেন্ট তথ্য, ট্যাক্স রেট, স্টাফ ম্যানেজমেন্ট |
| 🔐 | **রোল-ভিত্তিক অ্যাক্সেস** | Admin / Cashier / Kitchen আলাদা পার্মিশন |

---

## 📦 ইনস্টলেশন গাইড

### 1️⃣ রিপোজিটরি ক্লোন করুন

```bash
git clone <repository-url> pos-restaurant
cd pos-restaurant
```

### 2️⃣ Composer ডিপেন্ডেন্সি ইনস্টল করুন

```bash
composer install
```

### 3️⃣ Environment কনফিগার করুন

```bash
cp .env.example .env
php artisan key:generate
```

`.env` ফাইলে নিচের সেটিংস নিশ্চিত করুন:

```env
APP_NAME="POS Restaurant"
APP_URL=http://localhost/pos-restaurant/public

DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

### 4️⃣ SQLite ডেটাবেজ তৈরি করুন

```bash
# Linux / Mac
touch database/database.sqlite

# Windows (PowerShell)
New-Item database/database.sqlite -ItemType File
```

### 5️⃣ মাইগ্রেশন ও সিডার রান করুন

```bash
php artisan migrate:fresh --seed
```

### 6️⃣ স্টোরেজ লিংক তৈরি করুন

```bash
php artisan storage:link
```

### 7️⃣ সার্ভার চালু করুন

```bash
php artisan serve
```

> 🌐 WAMP/XAMPP এর মাধ্যমে: `http://localhost/pos-restaurant/public`

---

## 🔑 লগইন তথ্য (Demo)

| রোল | ইমেইল | পাসওয়ার্ড | অ্যাক্সেস |
|-----|-------|-----------|-----------|
| 👑 **Admin** | admin@pos.com | password | সব কিছু |
| 💰 **Cashier** | cashier@pos.com | password | POS, অর্ডার, বিলিং |
| 👨‍🍳 **Kitchen** | kitchen@pos.com | password | কিচেন ডিসপ্লে |

---

## 🗄️ ডেটাবেজ স্কিমা

```
👤 users               — ব্যবহারকারী (role: admin/cashier/kitchen)
🏷️ categories          — মেনু ক্যাটাগরি (icon, color, sort_order)
🍔 menu_items          — মেনু আইটেম (price, preparation_time, image)
🪑 restaurant_tables   — টেবিল (capacity, location, status)
📋 orders              — অর্ডার (order_number, order_type, status, totals)
📝 order_items         — অর্ডার আইটেম (quantity, unit_price, total_price)
💳 payments            — পেমেন্ট (method: cash/card/bkash/nagad)
⚙️ settings            — সিস্টেম সেটিংস (key-value store)
```

---

## 🌐 রাউট সংক্ষেপ

| আইকন | মডিউল | URL | বিবরণ |
|------|-------|-----|--------|
| 🔐 | Login | `/login` | লগইন পেজ |
| 📊 | Dashboard | `/dashboard` | মূল ড্যাশবোর্ড |
| 🛒 | POS | `/pos` | অর্ডার ইন্টারফেস |
| 📋 | Orders | `/orders` | অর্ডার তালিকা |
| 👨‍🍳 | Kitchen | `/kitchen` | কিচেন ডিসপ্লে |
| 🪑 | Tables | `/tables` | টেবিল ম্যানেজমেন্ট |
| 🍔 | Menu | `/menu/categories` | মেনু ক্যাটাগরি |
| 📈 | Reports | `/reports` | বিক্রয় রিপোর্ট |
| ⚙️ | Settings | `/settings` | সিস্টেম সেটিংস |

---

## 🔄 অর্ডার স্ট্যাটাস ফ্লো

```
🟡 pending  →  🔵 confirmed  →  🟠 preparing  →  🟢 ready  →  🍽️ served  →  ✅ completed
                                                                             ↘  ❌ cancelled
```

---

## 💳 পেমেন্ট মেথড

| মেথড | আইকন | বিবরণ |
|------|------|--------|
| **নগদ (Cash)** | 💵 | সরাসরি নগদ পেমেন্ট |
| **কার্ড (Card)** | 💳 | ক্রেডিট/ডেবিট কার্ড |
| **bKash** | 📱 | মোবাইল ব্যাংকিং |
| **Nagad** | 📲 | মোবাইল ব্যাংকিং |

---

## ⚙️ ডিফল্ট সেটিংস

| সেটিং | মান |
|-------|-----|
| 🏪 রেস্টুরেন্টের নাম | মেঘনা রেস্টুরেন্ট |
| 💰 মুদ্রা | ৳ (Bangladeshi Taka) |
| 🧾 ট্যাক্স রেট | ৫% |
| 📞 ফোন | 01800-000000 |
| 📍 ঠিকানা | ঢাকা, বাংলাদেশ |

---

## 📁 প্রজেক্ট স্ট্রাকচার

```
📦 pos-restaurant/
├── 📂 app/
│   ├── 📂 Http/Controllers/     # সকল কন্ট্রোলার
│   └── 📂 Models/               # Eloquent মডেল
├── 📂 database/
│   ├── 📂 migrations/           # ডেটাবেজ মাইগ্রেশন
│   ├── 📂 seeders/              # ডেমো ডেটা সিডার
│   └── 🗄️ database.sqlite       # SQLite ডেটাবেজ
├── 📂 resources/views/
│   ├── 📂 layouts/              # মেইন লেআউট
│   ├── 📂 auth/                 # লগইন ভিউ
│   ├── 📂 dashboard/            # ড্যাশবোর্ড
│   ├── 📂 orders/               # POS ও অর্ডার
│   ├── 📂 menu/                 # মেনু ম্যানেজমেন্ট
│   ├── 📂 tables/               # টেবিল ম্যানেজমেন্ট
│   ├── 📂 kitchen/              # কিচেন ডিসপ্লে
│   ├── 📂 billing/              # বিলিং ও রিসিট
│   ├── 📂 reports/              # রিপোর্ট
│   └── 📂 settings/             # সেটিংস
├── 📂 routes/
│   └── 🛣️ web.php               # সকল রাউট
└── 📂 public/
    ├── 📄 docs.html             # HTML ডকুমেন্টেশন
    └── 🎬 video-script.html     # ভিডিও স্ক্রিপ্ট
```

---

## 📚 ডকুমেন্টেশন

| ফাইল | বিবরণ |
|------|--------|
| 📄 [DOCUMENTATION.md](DOCUMENTATION.md) | বিস্তারিত টেকনিক্যাল ডকুমেন্টেশন |
| 🌐 [public/docs.html](public/docs.html) | HTML ডকুমেন্টেশন (ব্রাউজারে দেখুন) |
| 🎬 [public/video-script.html](public/video-script.html) | ভিডিও স্ক্রিপ্ট ও স্ক্রিনকাস্ট গাইড |

---

## 📜 লাইসেন্স

এই প্রজেক্টটি [MIT লাইসেন্স](https://opensource.org/licenses/MIT) এর অধীনে ওপেন সোর্স।

---

<div align="center">

**🍽️ মেঘনা রেস্টুরেন্ট POS সিস্টেম — বাংলাদেশের রেস্টুরেন্টের জন্য তৈরি**

Made with ❤️ using Laravel & Bootstrap

</div>
