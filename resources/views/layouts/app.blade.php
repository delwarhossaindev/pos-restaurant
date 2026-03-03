<!DOCTYPE html>
<html lang="bn" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'POS') - {{ config('app.name') }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #FF6B35;
            --primary-dark: #e55a28;
            --sidebar-bg: #1a1a2e;
            --sidebar-text: #e0e0e0;
            --sidebar-hover: #16213e;
            --sidebar-active: #FF6B35;
            --card-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }
        * { font-family: 'Hind Siliguri', sans-serif; }
        body { background: #f0f2f5; min-height: 100vh; }

        /* Sidebar */
        .sidebar {
            width: 250px; min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed; top: 0; left: 0;
            z-index: 1000; transition: all 0.3s;
            overflow-y: auto;
        }
        .sidebar.collapsed { width: 70px; }
        .sidebar-brand {
            padding: 20px 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-brand h5 { color: var(--primary); margin: 0; font-weight: 700; font-size: 1.1rem; }
        .sidebar-brand small { color: #888; font-size: 0.7rem; }
        .sidebar .nav-link {
            color: var(--sidebar-text); padding: 12px 20px;
            border-radius: 0; display: flex; align-items: center; gap: 12px;
            transition: all 0.2s; font-size: 0.9rem; white-space: nowrap;
        }
        .sidebar .nav-link:hover { background: var(--sidebar-hover); color: #fff; }
        .sidebar .nav-link.active { background: var(--primary); color: #fff; }
        .sidebar .nav-link i { width: 20px; text-align: center; font-size: 1rem; flex-shrink: 0; }
        .sidebar .nav-section { padding: 10px 20px 5px; color: #555; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; }

        /* Main Content */
        .main-content { margin-left: 250px; transition: all 0.3s; }
        .main-content.expanded { margin-left: 70px; }

        /* Topbar */
        .topbar {
            background: #fff; padding: 12px 25px;
            border-bottom: 1px solid #eee;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 999;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .topbar .page-title { font-size: 1.1rem; font-weight: 600; color: #333; margin: 0; }

        /* Cards */
        .card { border: none; border-radius: 12px; box-shadow: var(--card-shadow); }
        .card-header { background: transparent; border-bottom: 1px solid #f0f0f0; font-weight: 600; }

        /* Stats Cards */
        .stat-card {
            border-radius: 12px; padding: 20px;
            color: #fff; position: relative; overflow: hidden;
        }
        .stat-card .icon { font-size: 2.5rem; opacity: 0.3; position: absolute; right: 15px; top: 15px; }
        .stat-card h3 { font-size: 1.8rem; font-weight: 700; margin: 0; }
        .stat-card p { margin: 0; opacity: 0.9; font-size: 0.85rem; }

        /* Buttons */
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-outline-primary { color: var(--primary); border-color: var(--primary); }
        .btn-outline-primary:hover { background: var(--primary); border-color: var(--primary); }

        /* Table Status Badges */
        .table-card {
            border-radius: 12px; cursor: pointer;
            transition: all 0.2s; position: relative;
        }
        .table-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }

        /* Alert */
        .alert { border-radius: 10px; border: none; }

        /* Content padding */
        .content-area { padding: 25px; }

        /* Mobile */
        @media (max-width: 768px) {
            .sidebar { width: 0; overflow: hidden; }
            .sidebar.mobile-open { width: 250px; }
            .main-content { margin-left: 0; }
        }

        /* Badge */
        .badge { font-size: 0.75rem; }

        /* Form controls */
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.15);
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
        ::-webkit-scrollbar-track { background: #f5f5f5; }
    </style>
    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <h5><i class="fas fa-utensils me-2"></i>@php echo \App\Models\Setting::getValue('restaurant_name', 'POS Restaurant') @endphp</h5>
        <small>Restaurant Management</small>
    </div>
    <nav>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> <span>ড্যাশবোর্ড</span>
        </a>

        <div class="nav-section">অর্ডার</div>
        <a href="{{ route('pos') }}" class="nav-link {{ request()->routeIs('pos') ? 'active' : '' }}">
            <i class="fas fa-cash-register"></i> <span>POS অর্ডার</span>
        </a>
        <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-list"></i> <span>সব অর্ডার</span>
        </a>
        <a href="{{ route('kitchen.index') }}" class="nav-link {{ request()->routeIs('kitchen.*') ? 'active' : '' }}">
            <i class="fas fa-fire"></i> <span>কিচেন ডিসপ্লে</span>
        </a>

        <div class="nav-section">ম্যানেজমেন্ট</div>
        <a href="{{ route('tables.index') }}" class="nav-link {{ request()->routeIs('tables.*') ? 'active' : '' }}">
            <i class="fas fa-chair"></i> <span>টেবিল</span>
        </a>
        <a href="{{ route('menu.categories') }}" class="nav-link {{ request()->routeIs('menu.*') ? 'active' : '' }}">
            <i class="fas fa-book-open"></i> <span>মেনু</span>
        </a>

        <div class="nav-section">রিপোর্ট</div>
        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i> <span>রিপোর্ট</span>
        </a>

        @if(auth()->user()->isAdmin())
        <div class="nav-section">এডমিন</div>
        <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i> <span>সেটিং</span>
        </a>
        @endif

        <div style="height: 80px;"></div>

        <div class="nav-section">অ্যাকাউন্ট</div>
        <a href="{{ route('logout') }}" class="nav-link text-danger"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> <span>লগআউট</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </nav>
</div>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <!-- Topbar -->
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-outline-secondary" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <h6 class="page-title">@yield('page-title', 'ড্যাশবোর্ড')</h6>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-success fs-6" id="current-time"></span>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle me-1"></i> {{ auth()->user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-item-text text-muted small">{{ auth()->user()->role }}</span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>লগআউট
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    <div class="px-4 pt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Content -->
    @yield('content')
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar toggle
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('mainContent');
        sidebar.classList.toggle('collapsed');
        main.classList.toggle('expanded');
    }

    // Real-time clock
    function updateClock() {
        const now = new Date();
        document.getElementById('current-time').textContent =
            now.toLocaleTimeString('bn-BD', {hour: '2-digit', minute: '2-digit', second: '2-digit'});
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Setup AJAX CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    window.ajaxHeaders = {
        'X-CSRF-TOKEN': csrfToken,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    };
</script>
@stack('scripts')
</body>
</html>
