# রেস্টুরেন্ট পয়েন্ট অফ সেল (POS) সিস্টেম

বাংলাদেশের রেস্টুরেন্টগুলোর জন্য তৈরি একটি সম্পূর্ণ পয়েন্ট অফ সেল (POS) ম্যানেজমেন্ট সিস্টেম। Laravel 10 দিয়ে তৈরি এই সিস্টেমটি বাংলা ভাষায় সম্পূর্ণ UI সহ রেস্টুরেন্ট পরিচালনার সকল কাজ সহজ করে তোলে।

---

## প্রযুক্তি স্ট্যাক

| প্রযুক্তি | বিবরণ |
|-----------|--------|
| **PHP** | 8.3+ |
| **Laravel** | 10.x |
| **Database** | SQLite |
| **Frontend** | Bootstrap 5, Font Awesome 6 |
| **Font** | Hind Siliguri (বাংলা) |
| **Chart** | Chart.js |

---

## সিস্টেমের ফিচারসমূহ

- **ড্যাশবোর্ড** — আজকের বিক্রয়, অর্ডার স্ট্যাটাস, টেবিল গ্রিড, সাপ্তাহিক বিক্রয় চার্ট
- **POS অর্ডার ইন্টারফেস** — ক্যাটাগরি ফিল্টার, কার্ট, পেমেন্ট মডাল, ডিসকাউন্ট
- **কিচেন ডিসপ্লে সিস্টেম (KDS)** — রিয়েল-টাইম অর্ডার ট্র্যাকিং, ডার্ক থিম
- **টেবিল ম্যানেজমেন্ট** — ফ্লোর প্ল্যান, স্ট্যাটাস আপডেট (খালি/ব্যস্ত/রিজার্ভ)
- **মেনু ম্যানেজমেন্ট** — ক্যাটাগরি ও আইটেম CRUD, ছবি আপলোড, ফিচার্ড আইটেম
- **বিলিং ও রিসিট** — ইনভয়েস প্রিন্ট, থার্মাল রিসিট, পেমেন্ট মেথড
- **রিপোর্ট** — দৈনিক/সাপ্তাহিক বিক্রয়, টপ আইটেম, পেমেন্ট মেথড বিশ্লেষণ
- **সেটিংস** — রেস্টুরেন্ট তথ্য, ট্যাক্স রেট, স্টাফ ম্যানেজমেন্ট
- **রোল-ভিত্তিক অ্যাক্সেস** — Admin / Cashier / Kitchen

---

## ইনস্টলেশন গাইড

### ধাপ ১ — রিপোজিটরি ক্লোন করুন

```bash
git clone <repository-url> pos-restaurant
cd pos-restaurant
```

### ধাপ ২ — Composer ডিপেন্ডেন্সি ইনস্টল করুন

```bash
composer install
```

### ধাপ ৩ — Environment কনফিগার করুন

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

### ধাপ ৪ — SQLite ডেটাবেজ তৈরি করুন

```bash
touch database/database.sqlite
```

> **Windows এ:** `New-Item database/database.sqlite -ItemType File`

### ধাপ ৫ — মাইগ্রেশন ও সিডার রান করুন

```bash
php artisan migrate:fresh --seed
```

### ধাপ ৬ — স্টোরেজ লিংক তৈরি করুন

```bash
php artisan storage:link
```

### ধাপ ৭ — সার্ভার চালু করুন

```bash
php artisan serve
```

অথবা WAMP/XAMPP এর মাধ্যমে: `http://localhost/pos-restaurant/public`

---

## লগইন তথ্য (Demo)

| রোল | ইমেইল | পাসওয়ার্ড |
|-----|-------|-----------|
| **Admin** | admin@pos.com | password |
| **Cashier** | cashier@pos.com | password |
| **Kitchen** | kitchen@pos.com | password |

---

## ডেটাবেজ স্কিমা

```
users               — ব্যবহারকারী (role: admin/cashier/kitchen)
categories          — মেনু ক্যাটাগরি (icon, color, sort_order)
menu_items          — মেনু আইটেম (price, preparation_time, image)
restaurant_tables   — টেবিল (capacity, location, status)
orders              — অর্ডার (order_number, order_type, status, totals)
order_items         — অর্ডার আইটেম (quantity, unit_price, total_price)
payments            — পেমেন্ট (method: cash/card/bkash/nagad)
settings            — সিস্টেম সেটিংস (key-value store)
```

---

## রাউট সংক্ষেপ

| মডিউল | URL | বিবরণ |
|-------|-----|--------|
| Login | `/login` | লগইন পেজ |
| Dashboard | `/dashboard` | মূল ড্যাশবোর্ড |
| POS | `/pos` | অর্ডার ইন্টারফেস |
| Orders | `/orders` | অর্ডার তালিকা |
| Kitchen | `/kitchen` | কিচেন ডিসপ্লে |
| Tables | `/tables` | টেবিল ম্যানেজমেন্ট |
| Menu | `/menu/categories` | মেনু ক্যাটাগরি |
| Reports | `/reports` | বিক্রয় রিপোর্ট |
| Settings | `/settings` | সিস্টেম সেটিংস |

---

## অর্ডার স্ট্যাটাস ফ্লো

```
pending → confirmed → preparing → ready → served → completed
                                                  ↘ cancelled
```

---

## পেমেন্ট মেথড

- নগদ (Cash)
- কার্ড (Card)
- bKash
- Nagad

---

## ডিফল্ট সেটিংস

| সেটিং | মান |
|-------|-----|
| রেস্টুরেন্টের নাম | মেঘনা রেস্টুরেন্ট |
| মুদ্রা | ৳ (Bangladeshi Taka) |
| ট্যাক্স রেট | ৫% |
| ফোন | 01800-000000 |
| ঠিকানা | ঢাকা, বাংলাদেশ |

---

## ডকুমেন্টেশন

| ফাইল | বিবরণ |
|------|--------|
| [DOCUMENTATION.md](DOCUMENTATION.md) | বিস্তারিত টেকনিক্যাল ডকুমেন্টেশন |
| [public/docs.html](public/docs.html) | HTML ডকুমেন্টেশন (ব্রাউজারে দেখুন) |
| [public/video-script.html](public/video-script.html) | ভিডিও স্ক্রিপ্ট ও স্ক্রিনকাস্ট গাইড |

---

## প্রজেক্ট স্ট্রাকচার

```
pos-restaurant/
├── app/
│   ├── Http/Controllers/     # সকল কন্ট্রোলার
│   └── Models/               # Eloquent মডেল
├── database/
│   ├── migrations/           # ডেটাবেজ মাইগ্রেশন
│   ├── seeders/              # ডেমো ডেটা সিডার
│   └── database.sqlite       # SQLite ডেটাবেজ
├── resources/views/
│   ├── layouts/              # মেইন লেআউট
│   ├── auth/                 # লগইন ভিউ
│   ├── dashboard/            # ড্যাশবোর্ড
│   ├── orders/               # POS ও অর্ডার
│   ├── menu/                 # মেনু ম্যানেজমেন্ট
│   ├── tables/               # টেবিল ম্যানেজমেন্ট
│   ├── kitchen/              # কিচেন ডিসপ্লে
│   ├── billing/              # বিলিং ও রিসিট
│   ├── reports/              # রিপোর্ট
│   └── settings/             # সেটিংস
├── routes/
│   └── web.php               # সকল রাউট
└── public/
    ├── docs.html             # HTML ডকুমেন্টেশন
    └── video-script.html     # ভিডিও স্ক্রিপ্ট
```

---

## লাইসেন্স

এই প্রজেক্টটি [MIT লাইসেন্স](https://opensource.org/licenses/MIT) এর অধীনে ওপেন সোর্স।
