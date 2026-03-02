<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 0px;
            --navbar-height: 60px;

            --color-bg: #f4f5f7;
            --color-sidebar-bg: #1a1d23;
            --color-sidebar-text: #9aa0ac;
            --color-sidebar-text-active: #ffffff;
            --color-sidebar-hover: rgba(255, 255, 255, 0.06);
            --color-sidebar-active: rgba(255, 255, 255, 0.10);
            --color-sidebar-border: rgba(255, 255, 255, 0.07);
            --color-accent: #4f6ef7;
            --color-accent-light: #eef1fe;
            --color-navbar-bg: #ffffff;
            --color-card-bg: #ffffff;
            --color-text: #1a1d23;
            --color-text-muted: #6b7280;
            --color-border: #e5e7eb;
            --shadow-card: 0 1px 3px rgba(0, 0, 0, 0.07), 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        * {
            box-sizing: border-box;
        }

        body {
            background-color: var(--color-bg);
            color: var(--color-text);
            font-size: 14px;
            margin: 0;
        }

        /* ===================== SIDEBAR ===================== */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background-color: var(--color-sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1040;
            transition: width 0.22s ease;
            overflow: hidden;
        }

        #sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        /* Brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 18px;
            height: var(--navbar-height);
            border-bottom: 1px solid var(--color-sidebar-border);
            text-decoration: none;
            flex-shrink: 0;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-brand-icon {
            width: 32px;
            height: 32px;
            background-color: var(--color-accent);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 16px;
            flex-shrink: 0;
        }

        .sidebar-brand-name {
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.02em;
        }

        /* Scroll Area */
        .sidebar-scroll {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 12px 0;
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 3px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        /* Labels */
        .sidebar-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.28);
            padding: 16px 20px 6px;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-item {
            padding: 2px 10px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 8px 10px;
            border-radius: 8px;
            color: var(--color-sidebar-text);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            transition: background-color 0.15s, color 0.15s;
        }

        .sidebar-link:hover {
            background-color: var(--color-sidebar-hover);
            color: var(--color-sidebar-text-active);
        }

        .sidebar-link.active {
            background-color: var(--color-sidebar-active);
            color: var(--color-sidebar-text-active);
        }

        .sidebar-link .bi {
            font-size: 16px;
            flex-shrink: 0;
            width: 20px;
            text-align: center;
        }

        .sidebar-link.active .bi {
            color: var(--color-accent);
        }

        .sidebar-link-text {
            flex: 1;
        }

        .sidebar-badge {
            background-color: var(--color-accent);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 20px;
            font-family: 'DM Mono', monospace;
        }

        .sidebar-arrow {
            font-size: 11px;
            transition: transform 0.2s;
            flex-shrink: 0;
        }

        .sidebar-link[aria-expanded="true"] .sidebar-arrow {
            transform: rotate(90deg);
        }

        /* Footer */
        .sidebar-footer {
            border-top: 1px solid var(--color-sidebar-border);
            padding: 12px 10px;
            flex-shrink: 0;
            overflow: hidden;
            white-space: nowrap;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.15s;
        }

        .sidebar-user:hover {
            background-color: var(--color-sidebar-hover);
        }

        .sidebar-avatar {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background-color: var(--color-accent);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .sidebar-user-name {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-role {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.32);
        }

        /* Collapsed state */
        #sidebar.collapsed .sidebar-label {
            opacity: 0;
        }

        #sidebar.collapsed .sidebar-link-text,
        #sidebar.collapsed .sidebar-badge,
        #sidebar.collapsed .sidebar-arrow,
        #sidebar.collapsed .sidebar-brand-name,
        #sidebar.collapsed .sidebar-user-info {
            opacity: 0;
        }

        #sidebar.collapsed .sidebar-link {
            padding: 8px;
            justify-content: center;
        }

        #sidebar.collapsed .sidebar-item {
            padding: 2px 8px;
        }

        #sidebar.collapsed .sidebar-user {
            justify-content: center;
        }

        #sidebar.collapsed .sidebar-brand {
            justify-content: center;
        }

        /* ===================== NAVBAR ===================== */
        #navbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--navbar-height);
            background-color: var(--color-navbar-bg);
            border-bottom: 1px solid var(--color-border);
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 12px;
            z-index: 1030;
            transition: left 0.22s ease;
        }

        #navbar.sidebar-collapsed {
            left: var(--sidebar-collapsed-width);
        }

        .navbar-toggle {
            background: none;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-text-muted);
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.15s, color 0.15s;
            flex-shrink: 0;
        }

        .navbar-toggle:hover {
            background-color: var(--color-bg);
            color: var(--color-text);
        }

        .navbar-breadcrumb {
            flex: 1;
        }

        .navbar-breadcrumb .breadcrumb {
            margin: 0;
            font-size: 13px;
        }

        .navbar-breadcrumb .breadcrumb-item+.breadcrumb-item::before {
            content: "/";
            color: var(--color-border);
        }

        .navbar-breadcrumb .breadcrumb-item a {
            color: var(--color-text-muted);
            text-decoration: none;
        }

        .navbar-breadcrumb .breadcrumb-item a:hover {
            color: var(--color-accent);
        }

        .navbar-breadcrumb .breadcrumb-item.active {
            color: var(--color-text);
            font-weight: 500;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .nav-icon-btn {
            position: relative;
            background: none;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-text-muted);
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.15s, color 0.15s;
        }

        .nav-icon-btn:hover {
            background-color: var(--color-bg);
            color: var(--color-text);
        }

        .nav-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background-color: #ef4444;
            border: 2px solid var(--color-navbar-bg);
        }

        .navbar-divider {
            width: 1px;
            height: 22px;
            background-color: var(--color-border);
            margin: 0 6px;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 8px 4px 4px;
            border-radius: 8px;
            cursor: pointer;
            border: none;
            background: none;
            transition: background-color 0.15s;
        }

        .navbar-user:hover {
            background-color: var(--color-bg);
        }

        .navbar-avatar {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background-color: var(--color-accent);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
        }

        .navbar-user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--color-text);
        }

        .navbar-user .bi-chevron-down {
            font-size: 11px;
            color: var(--color-text-muted);
        }

        /* ===================== MAIN CONTENT ===================== */
        #main-content {
            margin-left: var(--sidebar-width);
            padding-top: var(--navbar-height);
            min-height: 100vh;
            transition: margin-left 0.22s ease;
        }

        #main-content.sidebar-collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }

        .page-content {
            padding: 28px;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin: 0 0 2px;
        }

        .page-subtitle {
            font-size: 13px;
            color: var(--color-text-muted);
            margin: 0;
        }

        /* Cards */
        .card {
            border: 1px solid var(--color-border);
            border-radius: 12px;
            box-shadow: var(--shadow-card);
            background-color: var(--color-card-bg);
        }

        .card-header {
            background: none;
            border-bottom: 1px solid var(--color-border);
            padding: 16px 20px;
            font-weight: 600;
            font-size: 14px;
        }

        .card-body {
            padding: 20px;
        }

        .card-footer {
            background: none;
            border-top: 1px solid var(--color-border);
            padding: 14px 20px;
        }

        /* Stat Cards */
        .stat-card {
            background-color: var(--color-card-bg);
            border: 1px solid var(--color-border);
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow-card);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 14px;
        }

        .stat-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--color-text-muted);
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.03em;
            color: var(--color-text);
            font-family: 'DM Mono', monospace;
            line-height: 1;
            margin-bottom: 8px;
        }

        .stat-change {
            font-size: 12px;
            font-weight: 500;
        }

        .change-up {
            color: #16a34a;
        }

        .change-down {
            color: #dc2626;
        }

        .change-neutral {
            color: var(--color-text-muted);
        }

        /* Tables */
        .table {
            font-size: 13.5px;
        }

        .table thead th {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--color-text-muted);
            border-bottom: 1px solid var(--color-border);
            padding: 12px 16px;
            background-color: #fafafa;
        }

        .table tbody td {
            padding: 13px 16px;
            border-bottom: 1px solid var(--color-border);
            vertical-align: middle;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover td {
            background-color: #fafafa;
        }

        /* Badges */
        .badge-status {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .badge-active {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-inactive {
            background: #f1f5f9;
            color: #64748b;
        }

        .badge-pending {
            background: #fef9c3;
            color: #a16207;
        }

        .badge-danger {
            background: #fee2e2;
            color: #dc2626;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--color-accent);
            border-color: var(--color-accent);
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #3d5ce5;
            border-color: #3d5ce5;
        }

        /* Alerts */
        .alert {
            border-radius: 10px;
            font-size: 13.5px;
        }

        /* Overlay (mobile) */
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1039;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.22s;
        }

        /* ===================== RESPONSIVE ===================== */
        @media (max-width: 991.98px) {
            #sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width) !important;
                transition: transform 0.22s ease;
            }

            #sidebar.mobile-open {
                transform: translateX(0);
            }

            #sidebar-overlay {
                display: block;
            }

            #sidebar.mobile-open~#sidebar-overlay {
                opacity: 1;
                pointer-events: all;
            }

            #navbar {
                left: 0 !important;
            }

            #main-content {
                margin-left: 0 !important;
            }
        }

        @media (max-width: 767.98px) {
            .page-content {
                padding: 20px 16px;
            }

            .navbar-breadcrumb {
                display: none;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- ===================== SIDEBAR ===================== -->
    <aside id="sidebar">

        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <img src="{{ asset('asset/logo-republikkorp.png') }}" alt="Logo RepublikKorp" width="120px">
        </a>

        <div class="sidebar-scroll">

            <div class="sidebar-label">Main</div>

            <div class="sidebar-item">
                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house"></i>
                    <span class="sidebar-link-text">Dashboard</span>
                </a>
            </div>
            <div class="sidebar-item">
                <a href="{{ route('tracking.index') }}"
                    class="sidebar-link {{ request()->routeIs('tracking.*') ? 'active' : '' }}">
                    <i class="bi bi-globe-central-south-asia"></i>
                    <span class="sidebar-link-text">Asset Tracking</span>
                </a>
            </div>

            <div class="sidebar-label">Master & Transaction</div>

            <div class="sidebar-item">
                <a href="{{ route('master-assets.index') }}"
                    class="sidebar-link {{ request()->routeIs('master-assets.*') ? 'active' : '' }}">
                    <i class="bi bi-box2"></i>
                    <span class="sidebar-link-text">Master Asset</span>
                </a>
            </div>
            <div class="sidebar-item">
                <a href="{{ route('assets.index') }}"
                    class="sidebar-link {{ request()->routeIs('assets.*') ? 'active' : '' }}">
                    <i class="bi bi-boxes"></i>
                    <span class="sidebar-link-text">Assets</span>
                </a>
            </div>

            <div class="sidebar-label">Management</div>

            <div class="sidebar-item">
                <a href="{{ route('users.index') }}"
                    class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span class="sidebar-link-text">Users</span>
                </a>
            </div>

            {{-- <div class="sidebar-item">
                <a href="#" class="sidebar-link">
                    <i class="bi bi-bar-chart-line"></i>
                    <span class="sidebar-link-text">Reports</span>
                </a>
            </div> --}}

        </div>

        {{-- User Footer --}}
        <div class="sidebar-footer">
            <div class="dropdown dropup">
                <div class="sidebar-user" data-bs-toggle="dropdown">
                    <div class="sidebar-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'AU', 0, 2)) }}
                    </div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name">{{ auth()->user()->name ?? 'Admin User' }}</div>
                    </div>
                </div>
                <ul class="dropdown-menu dropdown-menu-dark mb-1"
                    style="min-width: 200px; border-radius: 10px; font-size: 13.5px;">
                    <li>
                        <a class="dropdown-item py-2" href="#">
                            <i class="bi bi-person me-2"></i>Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="#">
                            <i class="bi bi-gear me-2"></i>Settings
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

    </aside>

    <div id="sidebar-overlay" onclick="toggleMobileSidebar()"></div>

    <!-- ===================== NAVBAR ===================== -->
    <nav id="navbar">

        <button class="navbar-toggle" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>

        <div class="navbar-breadcrumb">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="#">Home</a>
                    </li>
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>

        <div class="navbar-actions ms-auto">



            <div class="navbar-divider"></div>

            {{-- User --}}
            <div class="dropdown">
                <button class="navbar-user" data-bs-toggle="dropdown">
                    <div class="navbar-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'AU', 0, 2)) }}
                    </div>
                    <span class="navbar-user-name d-none d-sm-block">
                        {{ auth()->user()->name ?? 'Admin User' }}
                    </span>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                    style="border-radius: 10px; min-width: 200px; font-size: 13.5px;
                       border: 1px solid var(--color-border);
                       box-shadow: 0 4px 20px rgba(0,0,0,0.1) !important;">
                    <li class="px-3 py-2 border-bottom">
                        <div style="font-weight: 600; font-size: 13px;">{{ auth()->user()->name ?? 'Admin User' }}
                        </div>
                        <div style="font-size: 12px; color: var(--color-text-muted);">
                            {{ auth()->user()->email ?? 'admin@example.com' }}</div>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 mb-1 text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>
    </nav>

    <!-- ===================== MAIN CONTENT ===================== -->
    <main id="main-content">
        <div class="page-content">

            {{-- Page Header --}}
            @hasSection('page-title')
                <div class="page-header d-flex align-items-start justify-content-between flex-wrap gap-3">
                    <div>
                        <h1 class="page-title">@yield('page-title')</h1>
                        @hasSection('page-subtitle')
                            <p class="page-subtitle">@yield('page-subtitle')</p>
                        @endif
                    </div>
                    @hasSection('page-actions')
                        <div class="d-flex gap-2 flex-wrap">
                            @yield('page-actions')
                        </div>
                    @endif
                </div>
            @endif
            {{-- Sweet Alert --}}
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: '{{ session('success') }}',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    });
                </script>
            @endif

            {{-- Main Yield --}}
            @yield('content')

        </div>
    </main>

    {{-- Sweet Alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const navbar = document.getElementById('navbar');
        const mainContent = document.getElementById('main-content');
        const overlay = document.getElementById('sidebar-overlay');
        const STORAGE_KEY = 'adminpanel_sidebar_collapsed';

        // Restore sidebar state
        if (localStorage.getItem(STORAGE_KEY) === 'true' && window.innerWidth >= 992) {
            sidebar.classList.add('collapsed');
            navbar.classList.add('sidebar-collapsed');
            mainContent.classList.add('sidebar-collapsed');
        }

        function toggleSidebar() {
            if (window.innerWidth < 992) {
                toggleMobileSidebar();
                return;
            }
            const isCollapsed = sidebar.classList.toggle('collapsed');
            navbar.classList.toggle('sidebar-collapsed', isCollapsed);
            mainContent.classList.toggle('sidebar-collapsed', isCollapsed);
            localStorage.setItem(STORAGE_KEY, isCollapsed);
        }

        function toggleMobileSidebar() {
            const isOpen = sidebar.classList.toggle('mobile-open');
            document.body.style.overflow = isOpen ? 'hidden' : '';
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 992) {
                sidebar.classList.remove('mobile-open');
                document.body.style.overflow = '';
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
