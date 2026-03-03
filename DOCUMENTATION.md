# POS Restaurant Management System
## সম্পূর্ণ ডকুমেন্টেশন

---

## বিষয়সূচি

1. [প্রজেক্ট পরিচিতি](#১-প্রজেক্ট-পরিচিতি)
2. [প্রযুক্তি স্ট্যাক](#২-প্রযুক্তি-স্ট্যাক)
3. [ইনস্টলেশন গাইড](#৩-ইনস্টলেশন-গাইড)
4. [লগইন তথ্য](#৪-লগইন-তথ্য)
5. [ডেটাবেস কাঠামো](#৫-ডেটাবেস-কাঠামো)
6. [ফিচার গাইড](#৬-ফিচার-গাইড)
7. [ফাইল স্ট্রাকচার](#৭-ফাইল-স্ট্রাকচার)
8. [API রুট তালিকা](#৮-api-রুট-তালিকা)
9. [স্টাফ ভূমিকা ও অ্যাক্সেস](#৯-স্টাফ-ভূমিকা-ও-অ্যাক্সেস)
10. [সমস্যা সমাধান](#১০-সমস্যা-সমাধান)

---

## ১. প্রজেক্ট পরিচিতি

**POS Restaurant Management System** একটি সম্পূর্ণ রেস্টুরেন্ট পরিচালনা সফটওয়্যার।
এটি দিয়ে অর্ডার নেওয়া, কিচেনে পাঠানো, বিল তৈরি এবং বিক্রির রিপোর্ট দেখা যায়।

### মূল সুবিধাসমূহ
- সম্পূর্ণ বাংলা ইন্টারফেস
- ইন্টারনেট ছাড়া অফলাইনে কাজ করে
- MySQL ছাড়াই SQLite দিয়ে চলে
- সব ডিভাইসে রেসপনসিভ (মোবাইল, ট্যাবলেট, কম্পিউটার)

---

## ২. প্রযুক্তি স্ট্যাক

| বিভাগ | প্রযুক্তি | সংস্করণ |
|---|---|---|
| Backend Framework | Laravel | 10.x |
| Programming Language | PHP | 8.3+ |
| Database | SQLite | 3.x |
| Frontend CSS | Bootstrap | 5.3 |
| Icons | Font Awesome | 6.5 |
| Fonts | Hind Siliguri (Google Fonts) | — |
| Charts | Chart.js | 4.x |
| AJAX | Vanilla JavaScript (Fetch API) | — |

---

## ৩. ইনস্টলেশন গাইড

### প্রয়োজনীয়তা
- PHP 8.1 বা তার উপরে
- Composer
- WAMP / XAMPP / Laragon (Windows)

### ধাপে ধাপে ইনস্টলেশন

#### ধাপ ১: প্রজেক্ট ফোল্ডারে যান
```
d:\wamp64\www\pos-restaurant\
```

#### ধাপ ২: `.env` ফাইল কনফিগারেশন
```env
APP_NAME="POS Restaurant"
APP_URL=http://localhost/pos-restaurant/public

DB_CONNECTION=sqlite
DB_DATABASE=D:\wamp64\www\pos-restaurant\database\database.sqlite
```

#### ধাপ ৩: Composer ডিপেন্ডেন্সি ইনস্টল (যদি না থাকে)
```bash
composer install
```

#### ধাপ ৪: Database তৈরি করুন
```bash
php artisan migrate:fresh --seed
```

#### ধাপ ৫: Storage Link তৈরি
```bash
php artisan storage:link
```

#### ধাপ ৬: ব্রাউজারে খুলুন
```
http://localhost/pos-restaurant/public
```

### পুনরায় সেটআপ করতে
```bash
# সব ডেটা মুছে নতুন করে শুরু
php artisan migrate:fresh --seed
```

---

## ৪. লগইন তথ্য

| ভূমিকা | ইমেইল | পাসওয়ার্ড | অ্যাক্সেস |
|---|---|---|---|
| এডমিন | admin@pos.com | password | সব কিছু |
| ক্যাশিয়ার | cashier@pos.com | password | অর্ডার, বিল, রিপোর্ট |
| কিচেন | kitchen@pos.com | password | শুধু কিচেন ডিসপ্লে |

> **নোট:** প্রথম লগইনের পরে সেটিং থেকে পাসওয়ার্ড পরিবর্তন করুন।

---

## ৫. ডেটাবেস কাঠামো

### টেবিল সমূহ

```
pos_restaurant (SQLite)
├── users                    — স্টাফ/ব্যবহারকারী
├── categories               — মেনু ক্যাটাগরি
├── menu_items               — মেনু আইটেম
├── restaurant_tables        — রেস্টুরেন্টের টেবিল
├── orders                   — অর্ডার
├── order_items              — অর্ডারের আইটেম
├── payments                 — পেমেন্ট
├── settings                 — সিস্টেম সেটিং
├── migrations               — (Laravel System)
├── personal_access_tokens   — (Laravel System)
├── failed_jobs              — (Laravel System)
└── password_reset_tokens    — (Laravel System)
```

### টেবিলের বিস্তারিত

#### `users` — স্টাফ তথ্য
| কলাম | ধরন | বিবরণ |
|---|---|---|
| id | INTEGER | প্রাথমিক কী |
| name | VARCHAR | নাম |
| email | VARCHAR | ইমেইল (unique) |
| phone | VARCHAR | ফোন নম্বর |
| role | ENUM | admin / manager / cashier / waiter / kitchen |
| is_active | BOOLEAN | সক্রিয় / নিষ্ক্রিয় |
| password | VARCHAR | এনক্রিপ্টেড পাসওয়ার্ড |

#### `categories` — মেনু ক্যাটাগরি
| কলাম | ধরন | বিবরণ |
|---|---|---|
| id | INTEGER | প্রাথমিক কী |
| name | VARCHAR | ক্যাটাগরির নাম |
| icon | VARCHAR | Font Awesome আইকন ক্লাস |
| color | VARCHAR | হেক্স কালার কোড |
| is_active | BOOLEAN | সক্রিয় / নিষ্ক্রিয় |
| sort_order | INTEGER | সাজানোর ক্রম |

#### `menu_items` — মেনু আইটেম
| কলাম | ধরন | বিবরণ |
|---|---|---|
| id | INTEGER | প্রাথমিক কী |
| category_id | INTEGER | ক্যাটাগরি ID (Foreign Key) |
| name | VARCHAR | আইটেমের নাম |
| description | TEXT | বিবরণ |
| price | DECIMAL(10,2) | দাম |
| image | VARCHAR | ছবির পাথ |
| is_available | BOOLEAN | পাওয়া যাচ্ছে কিনা |
| is_featured | BOOLEAN | বিশেষ আইটেম |
| preparation_time | INTEGER | প্রস্তুতির সময় (মিনিট) |

#### `restaurant_tables` — রেস্টুরেন্ট টেবিল
| কলাম | ধরন | বিবরণ |
|---|---|---|
| id | INTEGER | প্রাথমিক কী |
| table_number | VARCHAR | টেবিল নম্বর |
| capacity | INTEGER | আসন সংখ্যা |
| status | ENUM | available / occupied / reserved / cleaning |
| location | VARCHAR | Main Hall / VIP / Outdoor |

#### `orders` — অর্ডার
| কলাম | ধরন | বিবরণ |
|---|---|---|
| id | INTEGER | প্রাথমিক কী |
| order_number | VARCHAR | অর্ডার নম্বর (ORD-YYYYMMDD-0001) |
| restaurant_table_id | INTEGER | টেবিল ID (nullable) |
| user_id | INTEGER | স্টাফ ID |
| order_type | ENUM | dine_in / takeaway / delivery |
| status | ENUM | pending / confirmed / preparing / ready / served / completed / cancelled |
| guests | INTEGER | অতিথি সংখ্যা |
| subtotal | DECIMAL | সাবটোটাল |
| tax_amount | DECIMAL | ভ্যাটের পরিমাণ |
| discount_amount | DECIMAL | ছাড়ের পরিমাণ |
| total_amount | DECIMAL | মোট পরিমাণ |
| notes | TEXT | বিশেষ নোট |
| customer_name | VARCHAR | কাস্টমারের নাম (টেকওয়ে/ডেলিভারির জন্য) |
| customer_phone | VARCHAR | কাস্টমারের ফোন |

#### `order_items` — অর্ডারের আইটেম
| কলাম | ধরন | বিবরণ |
|---|---|---|
| id | INTEGER | প্রাথমিক কী |
| order_id | INTEGER | অর্ডার ID |
| menu_item_id | INTEGER | মেনু আইটেম ID |
| quantity | INTEGER | পরিমাণ |
| unit_price | DECIMAL | একক দাম |
| total_price | DECIMAL | মোট দাম |
| notes | TEXT | বিশেষ নির্দেশনা |
| status | ENUM | pending / preparing / ready / served |

#### `payments` — পেমেন্ট
| কলাম | ধরন | বিবরণ |
|---|---|---|
| id | INTEGER | প্রাথমিক কী |
| order_id | INTEGER | অর্ডার ID |
| user_id | INTEGER | ক্যাশিয়ার ID |
| amount | DECIMAL | বিলের পরিমাণ |
| paid_amount | DECIMAL | প্রদত্ত পরিমাণ |
| change_amount | DECIMAL | ফেরতের পরিমাণ |
| method | ENUM | cash / card / mobile_banking / bkash / nagad |
| transaction_id | VARCHAR | ট্রানজেকশন ID (কার্ড/বিকাশের জন্য) |
| status | ENUM | pending / completed / failed / refunded |

#### `settings` — সিস্টেম সেটিং
| কলাম | ধরন | বিবরণ |
|---|---|---|
| id | INTEGER | প্রাথমিক কী |
| key | VARCHAR | সেটিং কী (unique) |
| value | TEXT | সেটিং মান |

**ডিফল্ট সেটিং কী সমূহ:**
- `restaurant_name` — রেস্টুরেন্টের নাম
- `restaurant_phone` — ফোন নম্বর
- `restaurant_address` — ঠিকানা
- `restaurant_email` — ইমেইল
- `tax_rate` — ভ্যাটের হার (%)
- `currency` — মুদ্রা (৳)
- `receipt_header` — রিসিটের হেডার
- `receipt_footer` — রিসিটের ফুটার

---

## ৬. ফিচার গাইড

### ৬.১ ড্যাশবোর্ড (`/`)

ড্যাশবোর্ডে একনজরে দেখা যায়:

| উইজেট | বিবরণ |
|---|---|
| আজকের বিক্রি | আজকের মোট পেমেন্টের যোগফল |
| আজকের অর্ডার | আজকের মোট অর্ডার সংখ্যা |
| সক্রিয় অর্ডার | এখন চলমান অর্ডার |
| খালি টেবিল | খালি / মোট টেবিল সংখ্যা |
| টেবিল গ্রিড | সব টেবিলের রিয়েল-টাইম অবস্থা |
| সাপ্তাহিক গ্রাফ | গত ৭ দিনের বিক্রির বার চার্ট |
| সেরা আইটেম | সবচেয়ে বেশি বিক্রিত আইটেম |
| সাম্প্রতিক অর্ডার | শেষ ১০টি অর্ডারের তালিকা |

---

### ৬.২ POS অর্ডার ইন্টারফেস (`/pos`)

**কিভাবে অর্ডার নেবেন:**

1. **অর্ডারের ধরন বেছে নিন** — ডাইন ইন / টেকওয়ে / ডেলিভারি
2. **টেবিল বেছে নিন** (ডাইন ইনের জন্য)
3. **ক্যাটাগরি ট্যাব** থেকে বিভাগ বেছে আইটেম যোগ করুন
4. **কার্টে পরিমাণ** কম-বেশি করুন (+/- বাটন)
5. **ছাড় দিতে** — ছাড়ের ঘরে পরিমাণ লিখুন
6. **"অর্ডার দিন"** বাটনে ক্লিক করুন
7. **পেমেন্ট পদ্ধতি** বেছে পেমেন্ট নিশ্চিত করুন
8. **রিসিট** স্বয়ংক্রিয়ভাবে দেখাবে

**পেমেন্ট পদ্ধতি:**
- নগদ (Cash)
- কার্ড (Card)
- বিকাশ (bKash)
- নগদ অ্যাপ (Nagad)

---

### ৬.৩ অর্ডার ম্যানেজমেন্ট (`/orders`)

অর্ডারের ধাপসমূহ (Status Flow):

```
অপেক্ষায় (pending)
    ↓
নিশ্চিত (confirmed)
    ↓
তৈরি হচ্ছে (preparing)
    ↓
প্রস্তুত (ready)
    ↓
পরিবেশিত (served)
    ↓
সম্পন্ন (completed)   ←← পেমেন্টের পরে স্বয়ংক্রিয়
```
বা
```
বাতিল (cancelled)     ←← যেকোনো ধাপ থেকে
```

---

### ৬.৪ কিচেন ডিসপ্লে সিস্টেম (`/kitchen`)

- প্রতি মিনিটে স্বয়ংক্রিয়ভাবে রিফ্রেশ হয়
- **হলুদ কার্ড** — নতুন অর্ডার, নিশ্চিত করতে হবে
- **নীল কার্ড** — নিশ্চিত, রান্না শুরু হয়নি
- **সবুজ কার্ড** — রান্না চলছে
- **টাইমার** — অর্ডার আসার পর থেকে সময় গণনা
  - ১৫ মিনিট পর: কমলা রঙ (সতর্কতা)
  - ৩০ মিনিট পর: লাল রঙ (বিলম্বিত)

**কিচেন অ্যাকশন:**
- "নিশ্চিত করুন" — অর্ডার কনফার্ম
- "রান্না শুরু" — রান্না শুরু হয়েছে
- "প্রস্তুত!" — খাবার তৈরি

---

### ৬.৫ টেবিল ম্যানেজমেন্ট (`/tables`)

**টেবিলের অবস্থা:**

| অবস্থা | রঙ | অর্থ |
|---|---|---|
| খালি (available) | সবুজ | অর্ডার নেওয়া যাবে |
| ব্যস্ত (occupied) | লাল | অর্ডার চলছে |
| রিজার্ভ (reserved) | হলুদ | বুকিং আছে |
| পরিষ্কার (cleaning) | নীল | পরিষ্কার হচ্ছে |

**টেবিলের অবস্থান:**
- Main Hall (প্রধান হল)
- VIP রুম
- Outdoor (বাইরে)
- Rooftop
- Private Room

---

### ৬.৬ মেনু ম্যানেজমেন্ট

#### ক্যাটাগরি (`/menu/categories`)
- নতুন ক্যাটাগরি যোগ
- Font Awesome আইকন সেট করুন
- কাস্টম রঙ নির্বাচন
- ক্যাটাগরি সক্রিয়/নিষ্ক্রিয় করুন

**জনপ্রিয় আইকন কোড:**
```
বার্গার:    fas fa-hamburger
পিৎজা:     fas fa-pizza-slice
চিকেন:     fas fa-drumstick-bite
ভাত:       fas fa-bread-slice
পানীয়:    fas fa-coffee
ডেজার্ট:   fas fa-ice-cream
মাছ:       fas fa-fish
স্যালাড:   fas fa-leaf
```

#### মেনু আইটেম (`/menu/items`)
- নতুন আইটেম যোগ (নাম, দাম, ক্যাটাগরি, ছবি)
- আইটেম সম্পাদনা
- আইটেম পাওয়া যাচ্ছে না — pause করুন
- বিশেষ আইটেম (⭐) হিসেবে চিহ্নিত করুন

---

### ৬.৭ বিলিং ও রিসিট (`/billing/{order}`)

**বিল প্রক্রিয়া:**
1. অর্ডার তালিকা থেকে অর্ডার খুলুন
2. "বিল করুন" বাটনে ক্লিক করুন
3. পেমেন্ট পদ্ধতি বেছে নিন
4. টাকার পরিমাণ লিখুন (নগদের জন্য)
5. ফেরতের পরিমাণ স্বয়ংক্রিয় দেখাবে
6. "পেমেন্ট নিশ্চিত করুন" ক্লিক করুন
7. রিসিট দেখাবে — প্রিন্ট করুন

**রিসিটে থাকে:**
- রেস্টুরেন্টের নাম ও ঠিকানা
- অর্ডার নম্বর, তারিখ, সময়
- সব আইটেমের তালিকা ও দাম
- সাবটোটাল, ভ্যাট, ছাড়, মোট
- পেমেন্টের তথ্য ও ফেরতের পরিমাণ
- ধন্যবাদ বার্তা

---

### ৬.৮ রিপোর্ট (`/reports`)

**ফিল্টার:** তারিখ পরিসর অনুযায়ী

**রিপোর্টে থাকে:**
- মোট বিক্রির পরিমাণ
- মোট অর্ডার সংখ্যা
- গড় অর্ডার মূল্য
- বাতিল অর্ডার সংখ্যা
- দৈনিক বিক্রির লাইন গ্রাফ
- পেমেন্ট পদ্ধতির পাই চার্ট
- সেরা বিক্রিত ১০টি আইটেম
- অর্ডারের ধরন অনুযায়ী বিশ্লেষণ

**দ্রুত ফিল্টার বাটন:**
- আজ
- এই সপ্তাহ
- এই মাস

---

### ৬.৯ সেটিং (`/settings`) — শুধু Admin

**রেস্টুরেন্ট সেটিং:**
- রেস্টুরেন্টের নাম
- ফোন, ইমেইল, ঠিকানা
- ভ্যাটের হার (%)
- মুদ্রা প্রতীক
- রিসিটের হেডার ও ফুটার

**স্টাফ ম্যানেজমেন্ট:**
- নতুন স্টাফ যোগ
- স্টাফের ভূমিকা পরিবর্তন
- পাসওয়ার্ড রিসেট
- স্টাফ সক্রিয়/নিষ্ক্রিয় করুন
- স্টাফ মুছুন

---

## ৭. ফাইল স্ট্রাকচার

```
pos-restaurant/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php          ← লগইন/লগআউট
│   │       ├── DashboardController.php     ← ড্যাশবোর্ড
│   │       ├── CategoryController.php      ← মেনু ক্যাটাগরি
│   │       ├── MenuItemController.php      ← মেনু আইটেম
│   │       ├── TableController.php         ← রেস্টুরেন্ট টেবিল
│   │       ├── OrderController.php         ← অর্ডার
│   │       ├── PaymentController.php       ← পেমেন্ট ও রিসিট
│   │       ├── KitchenController.php       ← কিচেন ডিসপ্লে
│   │       ├── ReportController.php        ← রিপোর্ট
│   │       └── SettingController.php       ← সেটিং ও স্টাফ
│   │
│   ├── Models/
│   │   ├── User.php
│   │   ├── Category.php
│   │   ├── MenuItem.php
│   │   ├── RestaurantTable.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Payment.php
│   │   └── Setting.php
│   │
│   └── Providers/
│       └── AppServiceProvider.php
│
├── database/
│   ├── database.sqlite                     ← SQLite ডেটাবেস ফাইল
│   ├── migrations/                         ← ডেটাবেস স্কিমা
│   └── seeders/
│       └── DatabaseSeeder.php              ← ডেমো ডেটা
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php               ← মূল লেআউট (Sidebar + Topbar)
│       ├── auth/
│       │   └── login.blade.php             ← লগইন পেজ
│       ├── dashboard/
│       │   └── index.blade.php             ← ড্যাশবোর্ড
│       ├── orders/
│       │   ├── pos.blade.php               ← POS অর্ডার স্ক্রিন
│       │   ├── index.blade.php             ← সব অর্ডার
│       │   └── show.blade.php              ← অর্ডার বিস্তারিত
│       ├── menu/
│       │   ├── categories.blade.php        ← ক্যাটাগরি ম্যানেজমেন্ট
│       │   └── items.blade.php             ← আইটেম ম্যানেজমেন্ট
│       ├── tables/
│       │   └── index.blade.php             ← টেবিল ফ্লোর প্ল্যান
│       ├── kitchen/
│       │   └── index.blade.php             ← কিচেন ডিসপ্লে
│       ├── billing/
│       │   ├── show.blade.php              ← বিল পেমেন্ট
│       │   ├── receipt.blade.php           ← রিসিট ভিউ
│       │   └── print.blade.php             ← প্রিন্টযোগ্য রিসিট
│       ├── reports/
│       │   └── index.blade.php             ← বিক্রির রিপোর্ট
│       └── settings/
│           └── index.blade.php             ← সিস্টেম সেটিং
│
├── routes/
│   └── web.php                             ← সব URL রুট
│
├── storage/
│   └── app/public/menu-items/              ← আইটেমের ছবি
│
├── .env                                    ← পরিবেশ কনফিগারেশন
└── DOCUMENTATION.md                        ← এই ফাইল
```

---

## ৮. API রুট তালিকা

### Auth
| Method | URL | বিবরণ |
|---|---|---|
| GET | `/login` | লগইন পেজ |
| POST | `/login` | লগইন প্রক্রিয়া |
| GET/POST | `/logout` | লগআউট |

### Dashboard
| Method | URL | বিবরণ |
|---|---|---|
| GET | `/` | ড্যাশবোর্ড |

### POS & Orders
| Method | URL | বিবরণ |
|---|---|---|
| GET | `/pos` | POS অর্ডার স্ক্রিন |
| GET | `/orders` | সব অর্ডার তালিকা |
| POST | `/orders` | নতুন অর্ডার তৈরি (AJAX) |
| GET | `/orders/{id}` | অর্ডার বিস্তারিত |
| POST | `/orders/{id}/status` | অর্ডারের অবস্থা পরিবর্তন (AJAX) |
| POST | `/orders/{id}/discount` | ছাড় প্রয়োগ (AJAX) |
| DELETE | `/orders/{id}` | অর্ডার মুছুন |

### Billing & Payment
| Method | URL | বিবরণ |
|---|---|---|
| GET | `/billing/{id}` | বিল পেমেন্ট পেজ |
| POST | `/billing/{id}/pay` | পেমেন্ট প্রক্রিয়া (AJAX) |
| GET | `/payment/{id}/receipt` | রিসিট দেখুন |
| GET | `/payment/{id}/print` | রিসিট প্রিন্ট |

### Kitchen
| Method | URL | বিবরণ |
|---|---|---|
| GET | `/kitchen` | কিচেন ডিসপ্লে |
| POST | `/kitchen/{id}/status` | অর্ডারের অবস্থা (AJAX) |

### Tables
| Method | URL | বিবরণ |
|---|---|---|
| GET | `/tables` | টেবিল তালিকা |
| POST | `/tables` | নতুন টেবিল যোগ |
| PUT | `/tables/{id}` | টেবিল আপডেট |
| DELETE | `/tables/{id}` | টেবিল মুছুন |
| POST | `/tables/{id}/status` | টেবিলের অবস্থা (AJAX) |

### Menu
| Method | URL | বিবরণ |
|---|---|---|
| GET | `/menu/categories` | ক্যাটাগরি তালিকা |
| POST | `/menu/categories` | নতুন ক্যাটাগরি |
| PUT | `/menu/categories/{id}` | ক্যাটাগরি আপডেট |
| DELETE | `/menu/categories/{id}` | ক্যাটাগরি মুছুন |
| PATCH | `/menu/categories/{id}/toggle` | সক্রিয়/নিষ্ক্রিয় |
| GET | `/menu/items` | আইটেম তালিকা |
| POST | `/menu/items` | নতুন আইটেম |
| PUT | `/menu/items/{id}` | আইটেম আপডেট |
| DELETE | `/menu/items/{id}` | আইটেম মুছুন |
| PATCH | `/menu/items/{id}/toggle` | পাওয়া যাচ্ছে/না |

### Reports & Settings
| Method | URL | বিবরণ |
|---|---|---|
| GET | `/reports` | বিক্রির রিপোর্ট |
| GET | `/settings` | সেটিং পেজ |
| PUT | `/settings` | সেটিং আপডেট |
| POST | `/settings/users` | নতুন স্টাফ |
| PUT | `/settings/users/{id}` | স্টাফ আপডেট |
| DELETE | `/settings/users/{id}` | স্টাফ মুছুন |

---

## ৯. স্টাফ ভূমিকা ও অ্যাক্সেস

| ফিচার | Admin | Manager | Cashier | Waiter | Kitchen |
|---|:---:|:---:|:---:|:---:|:---:|
| ড্যাশবোর্ড | ✅ | ✅ | ✅ | ✅ | ❌ |
| POS অর্ডার | ✅ | ✅ | ✅ | ✅ | ❌ |
| অর্ডার দেখা | ✅ | ✅ | ✅ | ✅ | ❌ |
| বিলিং | ✅ | ✅ | ✅ | ❌ | ❌ |
| কিচেন ডিসপ্লে | ✅ | ✅ | ✅ | ✅ | ✅ |
| টেবিল ম্যানেজমেন্ট | ✅ | ✅ | ✅ | ✅ | ❌ |
| মেনু ম্যানেজমেন্ট | ✅ | ✅ | ❌ | ❌ | ❌ |
| রিপোর্ট | ✅ | ✅ | ✅ | ❌ | ❌ |
| সেটিং | ✅ | ❌ | ❌ | ❌ | ❌ |
| স্টাফ যোগ/মুছুন | ✅ | ❌ | ❌ | ❌ | ❌ |

---

## ১০. সমস্যা সমাধান

### সমস্যা: লগইন হচ্ছে না
**সমাধান:**
```bash
php artisan cache:clear
php artisan config:clear
```

### সমস্যা: ৫০০ Server Error দেখাচ্ছে
**সমাধান:**
```bash
# .env ফাইলে APP_DEBUG=true করুন
# storage ফোল্ডারে অনুমতি দিন
chmod -R 775 storage bootstrap/cache
```

### সমস্যা: ছবি আপলোড হচ্ছে না
**সমাধান:**
```bash
php artisan storage:link
```

### সমস্যা: ডেটাবেস রিসেট করতে চান
**সমাধান:**
```bash
php artisan migrate:fresh --seed
```

### সমস্যা: SQLite ফাইল নেই
**সমাধান:**
```bash
# Windows PowerShell
New-Item database/database.sqlite
# অথবা bash
touch database/database.sqlite
```

### সমস্যা: রিপোর্টে ডেটা নেই
নিশ্চিত করুন:
1. অর্ডারের `status = completed` আছে
2. `payments` টেবিলে `status = completed` আছে
3. তারিখের ফিল্টার সঠিক দেওয়া আছে

### সমস্যা: কিচেন আপডেট হচ্ছে না
- পেজ ম্যানুয়ালি রিফ্রেশ করুন (F5)
- কিচেন পেজ প্রতি মিনিটে স্বয়ংক্রিয় রিফ্রেশ হয়

---

## ডেভেলপার তথ্য

| বিষয় | তথ্য |
|---|---|
| Framework | Laravel 10 |
| PHP Version | 8.3+ |
| Database | SQLite 3 |
| License | MIT |
| ভাষা | বাংলা (Bengali) |

---

*সর্বশেষ আপডেট: মার্চ ২০২৬*
