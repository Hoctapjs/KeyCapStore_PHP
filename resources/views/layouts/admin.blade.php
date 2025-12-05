<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - KeyCap Store</title>

    <link href="{{ asset('vendor/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: #2c3e50;
            color: white;
            overflow-y: auto;
            z-index: 1000;
        }

        .admin-sidebar .brand {
            padding: 20px;
            background: #1a252f;
            border-bottom: 1px solid #34495e;
        }

        .admin-sidebar .brand h4 {
            color: white;
            margin: 0;
            font-size: 1.2rem;
        }

        .admin-sidebar .nav-menu {
            padding: 20px 0;
        }

        .admin-sidebar .nav-item {
            display: block;
            padding: 12px 20px;
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .admin-sidebar .nav-item:hover {
            background: #34495e;
            border-left-color: #3498db;
            padding-left: 25px;
        }

        .admin-sidebar .nav-item.active {
            background: #34495e;
            border-left-color: #3498db;
            font-weight: 600;
        }

        .admin-sidebar .nav-item i {
            width: 20px;
            margin-right: 10px;
        }

        /* Main Content */
        .admin-content {
            margin-left: 260px;
            min-height: 100vh;
        }

        /* Topbar */
        .admin-topbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-topbar h5 {
            margin: 0;
            color: #2c3e50;
        }

        /* Content Area */
        .admin-main {
            padding: 30px;
        }

        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background: white;
            border-bottom: 2px solid #ecf0f1;
            font-weight: 600;
        }

        .btn-primary {
            background: #3498db;
            border-color: #3498db;
        }

        .btn-primary:hover {
            background: #2980b9;
            border-color: #2980b9;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="brand">
            <h4>🔑 KeyCap Admin</h4>
            <small class="text-muted">{{ auth()->user()->name }}</small>
        </div>

        <nav class="nav-menu">
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                📊 Dashboard
            </a>

            <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                📦 Quản lý sản phẩm
            </a>

            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                📁 Quản lý danh mục
            </a>

            <a href="{{ route('admin.brands.index') }}" class="nav-item {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                🏷️ Quản lý thương hiệu
            </a>
            @endif

            <a href="{{ route('admin.inventory.index') }}" class="nav-item {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                📈 Quản lý tồn kho
            </a>

            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.reviews.index') }}" class="nav-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                ⭐ Quản lý đánh giá
            </a>

            <a href="{{ route('admin.coupons.index') }}" class="nav-item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                🎫 Quản lý mã giảm giá
            </a>

            <a href="{{ route('admin.tags.index') }}" class="nav-item {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
                🏷️ Quản lý tag
            </a>
            @endif

            <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                📋 Quản lý đơn hàng
            </a>

            <hr style="border-color: #34495e; margin: 20px 0;">

            <a href="{{ route('home') }}" class="nav-item" target="_blank">
                🏠 Xem website
            </a>

            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="nav-item w-100 text-start border-0 bg-transparent" style="color: #ecf0f1;">
                    🚪 Đăng xuất
                </button>
            </form>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="admin-content">
        <!-- Topbar -->
        <div class="admin-topbar">
            <h5>@yield('page-title', 'Dashboard')</h5>
            <div>
                <span class="badge bg-primary">{{ auth()->user()->role }}</span>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="admin-main">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="{{ asset('vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/jquery-1.11.0.min.js') }}"></script>

    @stack('scripts')
</body>
</html>
