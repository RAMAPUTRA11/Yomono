<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>YOMONO — Admin Panel</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            color: #111111;
            display: flex;
            min-height: 100vh;
        }

        /* --- SIDEBAR KIRI (SESUAI MOCKUP ASLI YOMONO) --- */
        .yomono-sidebar {
            width: 240px;
            background-color: #ffffff;
            border-right: 1px solid #f0f0f0;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            padding: 40px 30px;
            z-index: 100;
        }
        
        .sidebar-brand {
            margin-bottom: 60px;
        }
        .sidebar-brand a {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #000000;
            text-decoration: none;
        }
        
        .sidebar-section-title {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.5px;
            color: #b0b0b0;
            text-transform: uppercase;
            margin-bottom: 25px;
        }

        .sidebar-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .sidebar-item a {
            color: #888888;
            text-decoration: none;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            transition: color 0.2s ease;
            display: block;
        }
        
        .sidebar-item a:hover {
            color: #000000;
        }
        
        /* State Menu Active: Menjadi Kotak Hitam sesuai Gambar Mockup */
        .sidebar-item.active a {
            color: #ffffff;
            background-color: #000000;
            padding: 12px 15px;
            margin-left: -15px;
            margin-right: -15px;
            font-weight: 600;
        }

        .sidebar-logout {
            margin-top: auto;
        }
        .logout-btn {
            background: none;
            border: none;
            color: #ff4d4d;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            cursor: pointer;
            padding: 0;
            text-align: left;
        }
        .logout-btn:hover {
            text-decoration: underline;
        }

        /* --- KONTEN SEBELAH KANAN --- */
        .yomono-main-content {
            flex: 1;
            margin-left: 240px; /* Jarak aman agar tidak tertutup sidebar */
            padding: 40px 50px;
            background-color: #ffffff;
            min-width: 0;
        }

        /* Navigasi Atas Toko (Opsional / Sinkronisasi Header) */
        .top-shop-nav {
            display: flex;
            justify-content: flex-end;
            gap: 30px;
            align-items: center;
            margin-bottom: 40px;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        .top-shop-nav a {
            color: #000000;
            text-decoration: none;
        }

        /* Reset Kelengkungan Siku Bootstrap agar Sesuai Tema Kotak Minimalis */
        .modal-content, .form-select, .form-control {
            border-radius: 0px !important;
        }
    </style>
</head>
<body>

    <div class="yomono-sidebar">
        <div class="sidebar-brand">
            <a href="#">YOMONO</a>
        </div>
        
        <div class="sidebar-section-title">Administrator</div>
        
        <ul class="sidebar-menu">
            <li class="sidebar-item {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="sidebar-item {{ Request::is('admin/categories*') ? 'active' : '' }}">
                <a href="{{ route('admin.categories.index') }}">Manage Categories</a>
            </li>
            <li class="sidebar-item {{ Request::is('admin/products*') ? 'active' : '' }}">
                <a href="{{ route('admin.products.index') }}">Manage Products</a>
            </li>
            <li class="sidebar-item {{ Request::is('admin/orders*') ? 'active' : '' }}">
                <a href="{{ route('admin.orders.index') }}">Transactions</a>
            </li>
            <li class="sidebar-item {{ Request::is('admin/attributes*') ? 'active' : '' }}">
                <a href="{{ route('admin.attributes.index') }}">Manage Attributes</a>
            </li>
        </ul>

        <div class="sidebar-logout">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <div class="yomono-main-content">
        <div class="top-shop-nav">
            <a href="{{ route('shop') }}" target="_blank">Semua Kategori</a>
            <a href="{{ route('shop') }}" target="_blank">Shop</a>
            <div style="color: #666; font-size: 11px;">
                ADMIN YOMONO <i class="fa fa-chevron-down" style="font-size: 9px; margin-left: 5px;"></i>
            </div>
        </div>

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>