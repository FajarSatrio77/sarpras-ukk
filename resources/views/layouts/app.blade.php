<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SARPRAS SMK</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #0ea5e9;
            --dark: #0f172a;
            --light: #f8fafc;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --nav-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f0f4ff 0%, #faf5ff 50%, #fdf4ff 100%);
            min-height: 100vh;
            color: var(--gray-700);
        }

        /* Top Navigation */
        .top-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--nav-height);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(99, 102, 241, 0.1);
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 0 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            margin-right: 40px;
        }

        .nav-brand img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .nav-brand-text {
            font-weight: 700;
            font-size: 1.25rem;
            background: linear-gradient(135deg, var(--primary) 0%, #a855f7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 4px;
            flex: 1;
        }

        .nav-item {
            position: relative;
            padding: 10px 16px;
            color: var(--gray-600);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            border-radius: 10px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-item:hover {
            color: var(--primary);
            background: rgba(99, 102, 241, 0.08);
        }

        .nav-item.active {
            color: var(--primary);
            background: rgba(99, 102, 241, 0.1);
        }

        .nav-item i {
            font-size: 1.1rem;
        }

        /* Dropdown */
        .nav-dropdown {
            position: relative;
        }

        .nav-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            background: white;
            border-radius: 14px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
            min-width: 200px;
            padding: 8px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease;
            border: 1px solid var(--gray-100);
        }

        .nav-dropdown:hover .nav-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .nav-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            color: var(--gray-600);
            text-decoration: none;
            border-radius: 10px;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .nav-dropdown-item:hover {
            background: var(--gray-50);
            color: var(--primary);
        }

        .nav-dropdown-item.active {
            background: rgba(99, 102, 241, 0.08);
            color: var(--primary);
        }

        .nav-dropdown-item i {
            font-size: 1rem;
            width: 20px;
        }

        /* User Section */
        .nav-user {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-left: auto;
        }

        .user-dropdown {
            position: relative;
        }

        .user-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px 6px 6px;
            background: var(--gray-50);
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .user-btn:hover {
            border-color: var(--gray-200);
            background: white;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #a855f7);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .user-info {
            text-align: left;
        }

        .user-name {
            font-weight: 600;
            color: var(--gray-800);
            font-size: 0.875rem;
        }

        .user-role {
            font-size: 0.7rem;
            color: var(--gray-500);
            text-transform: capitalize;
        }

        .user-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: white;
            border-radius: 14px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
            min-width: 200px;
            padding: 8px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease;
            border: 1px solid var(--gray-100);
        }

        .user-dropdown:hover .user-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-divider {
            height: 1px;
            background: var(--gray-100);
            margin: 6px 0;
        }

        /* Main Content */
        .main-wrapper {
            padding-top: var(--nav-height);
            min-height: 100vh;
        }

        .content-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            border: 1px solid rgba(99, 102, 241, 0.06);
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-800);
        }

        .card-body {
            padding: 24px;
        }

        /* Stat Cards */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
            border: 1px solid rgba(99, 102, 241, 0.06);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(99, 102, 241, 0.12);
            border-color: rgba(99, 102, 241, 0.15);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .stat-icon.primary { background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(168, 85, 247, 0.15)); color: var(--primary); }
        .stat-icon.success { background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(5, 150, 105, 0.15)); color: var(--success); }
        .stat-icon.warning { background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(217, 119, 6, 0.15)); color: var(--warning); }
        .stat-icon.danger { background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(220, 38, 38, 0.15)); color: var(--danger); }
        .stat-icon.info { background: linear-gradient(135deg, rgba(14, 165, 233, 0.15), rgba(2, 132, 199, 0.15)); color: var(--info); }

        .stat-content h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--gray-800);
        }

        .stat-content p {
            color: var(--gray-500);
            font-size: 0.875rem;
            margin-top: 2px;
        }

        /* Grid */
        .grid {
            display: grid;
            gap: 20px;
        }

        .grid-4 { grid-template-columns: repeat(4, 1fr); }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-2 { grid-template-columns: repeat(2, 1fr); }

        @media (max-width: 1200px) {
            .grid-4 { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .grid-4, .grid-3, .grid-2 { grid-template-columns: 1fr; }
        }

        /* Table */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 14px 20px;
            text-align: left;
            border-bottom: 1px solid var(--gray-100);
        }

        .table th {
            font-weight: 600;
            color: var(--gray-500);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: var(--gray-50);
        }

        .table tbody tr {
            transition: background 0.2s;
        }

        .table tbody tr:hover {
            background: var(--gray-50);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            gap: 5px;
        }

        .badge-success { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .badge-warning { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .badge-danger { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
        .badge-info { background: rgba(14, 165, 233, 0.1); color: var(--info); }
        .badge-primary { background: rgba(99, 102, 241, 0.1); color: var(--primary); }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }

        .btn-outline {
            background: white;
            border: 1px solid var(--gray-200);
            color: var(--gray-600);
        }

        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: rgba(99, 102, 241, 0.04);
        }

        /* Alerts */
        .alert {
            padding: 16px 20px;
            border-radius: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #047857;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #b91c1c;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* Pagination */
        nav[role="navigation"] {
            display: flex;
            justify-content: center;
        }

        nav[role="navigation"] > div {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        nav[role="navigation"] > div > div:first-child {
            display: none;
        }

        nav[role="navigation"] span[aria-current="page"] span,
        nav[role="navigation"] a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        nav[role="navigation"] span[aria-current="page"] span {
            background: var(--primary);
            color: white;
        }

        nav[role="navigation"] a {
            background: white;
            color: var(--gray-500);
            border: 1px solid var(--gray-200);
        }

        nav[role="navigation"] a:hover {
            background: var(--gray-50);
            border-color: var(--primary);
            color: var(--primary);
        }

        nav[role="navigation"] svg {
            width: 16px !important;
            height: 16px !important;
        }

        /* Simple pagination */
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 6px;
            justify-content: center;
        }

        .pagination li a,
        .pagination li span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            background: white;
            color: var(--gray-500);
            border: 1px solid var(--gray-200);
        }

        .pagination li.active span {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .pagination li a:hover {
            background: var(--gray-50);
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Mobile Menu Toggle */
        .mobile-toggle {
            display: none;
            padding: 10px 12px;
            background: var(--gray-100);
            border: none;
            border-radius: 10px;
            font-size: 1.5rem;
            color: var(--primary);
            cursor: pointer;
            margin-left: auto;
            margin-right: 12px;
        }

        .mobile-toggle:active {
            background: var(--gray-200);
        }

        /* Mobile Overlay */
        .mobile-overlay {
            display: none;
            position: fixed;
            top: var(--nav-height);
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
        }

        .mobile-overlay.active {
            display: block;
        }

        /* Tablet and below */
        @media (max-width: 1024px) {
            .nav-menu {
                position: fixed;
                top: var(--nav-height);
                left: 0;
                right: 0;
                bottom: 0;
                background: white;
                flex-direction: column;
                padding: 20px;
                gap: 8px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                overflow-y: auto;
                z-index: 999;
                align-items: stretch;
            }

            .nav-menu.active {
                transform: translateX(0);
            }

            .mobile-toggle {
                display: block;
            }

            .nav-item {
                width: 100%;
                justify-content: flex-start;
            }

            .nav-dropdown {
                width: 100%;
            }

            .nav-dropdown-menu {
                position: static;
                opacity: 1;
                visibility: visible;
                transform: none;
                box-shadow: none;
                border: none;
                padding-left: 20px;
                background: var(--gray-50);
                border-radius: 12px;
                margin-top: 4px;
            }

            .nav-brand {
                margin-right: 0;
                flex-shrink: 0;
            }

            .nav-brand-text {
                display: none;
            }

            .nav-user {
                margin-left: 0;
                flex-shrink: 0;
            }

            .user-info {
                display: none;
            }

            .user-btn {
                padding: 4px;
                background: transparent;
            }

            .user-dropdown-menu {
                right: 0;
            }

            .content-wrapper {
                padding: 16px;
            }
        }

        /* Mobile phones */
        @media (max-width: 768px) {
            :root {
                --nav-height: 60px;
            }

            .top-nav {
                padding: 0 12px;
            }

            .nav-brand img {
                width: 40px;
                height: 40px;
            }

            .user-avatar {
                width: 32px;
                height: 32px;
                font-size: 0.75rem;
            }

            .content-wrapper {
                padding: 12px;
            }

            .card {
                border-radius: 16px;
            }

            .card-body {
                padding: 16px;
            }

            .stat-card {
                padding: 16px;
                gap: 12px;
                border-radius: 16px;
            }

            .stat-icon {
                width: 48px;
                height: 48px;
                font-size: 1.2rem;
                border-radius: 12px;
            }

            .stat-content h3 {
                font-size: 1.4rem;
            }

            .stat-content p {
                font-size: 0.75rem;
            }

            .grid-4, .grid-3 {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .grid-2 {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .btn {
                padding: 10px 16px;
                font-size: 0.8rem;
            }

            .table th, .table td {
                padding: 10px 12px;
                font-size: 0.8rem;
            }

            h1 {
                font-size: 1.25rem !important;
            }

            .badge {
                padding: 4px 8px;
                font-size: 0.7rem;
            }
        }

        /* Very small phones */
        @media (max-width: 480px) {
            .grid-4, .grid-3, .grid-2 {
                grid-template-columns: 1fr;
            }

            .stat-card {
                flex-direction: column;
                text-align: center;
                gap: 8px;
            }

            .table {
                font-size: 0.75rem;
            }

            .table th, .table td {
                padding: 8px 10px;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .card-header {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Top Navigation -->
    <nav class="top-nav">
        <a href="{{ route('dashboard') }}" class="nav-brand">
            <img src="{{ asset('images/logosmea.png') }}" alt="Logo">
            <span class="nav-brand-text">SARPRASKITA</span>
        </a>

        <button class="mobile-toggle" onclick="toggleMobileMenu()">
            <i class="bi bi-list"></i>
        </button>

        <div class="nav-menu" id="navMenu">
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i>
                Dashboard
            </a>

            @if(auth()->user()->canManage())
            <div class="nav-dropdown">
                <a href="#" class="nav-item {{ request()->routeIs('users.*', 'kategori.*', 'sarpras.*') ? 'active' : '' }}">
                    <i class="bi bi-database"></i>
                    Data Barang
                    <i class="bi bi-chevron-down" style="font-size: 0.7rem; margin-left: 2px;"></i>
                </a>
                <div class="nav-dropdown-menu">
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('users.index') }}" class="nav-dropdown-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        Kelola User
                    </a>
                    @endif
                    <a href="{{ route('kategori.index') }}" class="nav-dropdown-item {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                        <i class="bi bi-folder"></i>
                        Kategori Barang
                    </a>
                    <a href="{{ route('sarpras.index') }}" class="nav-dropdown-item {{ request()->routeIs('sarpras.*') ? 'active' : '' }}">
                        <i class="bi bi-box"></i>
                        Data Barang
                    </a>
                </div>
            </div>
            @endif

            <div class="nav-dropdown">
                <a href="#" class="nav-item {{ request()->routeIs('peminjaman.*', 'pengembalian.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right"></i>
                    Transaksi
                    <i class="bi bi-chevron-down" style="font-size: 0.7rem; margin-left: 2px;"></i>
                </a>
                <div class="nav-dropdown-menu">
                    @if(auth()->user()->isPengguna())
                    <a href="{{ route('peminjaman.daftar') }}" class="nav-dropdown-item {{ request()->routeIs('peminjaman.daftar', 'peminjaman.create') ? 'active' : '' }}">
                        <i class="bi bi-cart-plus"></i>
                        Ajukan Peminjaman
                    </a>
                    <a href="{{ route('peminjaman.riwayat') }}" class="nav-dropdown-item {{ request()->routeIs('peminjaman.riwayat') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i>
                        Riwayat Peminjaman
                    </a>
                    @endif
                    @if(auth()->user()->canManage())
                    <a href="{{ route('peminjaman.index') }}" class="nav-dropdown-item {{ request()->routeIs('peminjaman.index', 'peminjaman.show') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-check"></i>
                        Kelola Peminjaman
                    </a>
                    <a href="{{ route('pengembalian.scan') }}" class="nav-dropdown-item {{ request()->routeIs('pengembalian.*') ? 'active' : '' }}">
                        <i class="bi bi-qr-code-scan"></i>
                        Proses Pengembalian
                    </a>
                    @endif
                </div>
            </div>

            <div class="nav-dropdown">
                <a href="#" class="nav-item {{ request()->routeIs('pengaduan.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-square-text"></i>
                    Pengaduan
                    <i class="bi bi-chevron-down" style="font-size: 0.7rem; margin-left: 2px;"></i>
                </a>
                <div class="nav-dropdown-menu">
                    @if(auth()->user()->isPengguna())
                    <a href="{{ route('pengaduan.create') }}" class="nav-dropdown-item {{ request()->routeIs('pengaduan.create') ? 'active' : '' }}">
                        <i class="bi bi-megaphone"></i>
                        Buat Pengaduan
                    </a>
                    @endif
                    <a href="{{ route('pengaduan.index') }}" class="nav-dropdown-item {{ request()->routeIs('pengaduan.index', 'pengaduan.show') ? 'active' : '' }}">
                        <i class="bi bi-list-ul"></i>
                        {{ auth()->user()->canManage() ? 'Kelola Pengaduan' : 'Riwayat Pengaduan' }}
                    </a>
                </div>
            </div>

            @if(auth()->user()->isAdmin())
            <div class="nav-dropdown">
                <a href="#" class="nav-item {{ request()->routeIs('laporan.*', 'activity.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart"></i>
                    Laporan
                    <i class="bi bi-chevron-down" style="font-size: 0.7rem; margin-left: 2px;"></i>
                </a>
                <div class="nav-dropdown-menu">
                    <a href="{{ route('laporan.kerusakan') }}" class="nav-dropdown-item {{ request()->routeIs('laporan.kerusakan') ? 'active' : '' }}">
                        <i class="bi bi-exclamation-octagon"></i>
                        Laporan Kerusakan
                    </a>
                    <a href="{{ route('activity.index') }}" class="nav-dropdown-item {{ request()->routeIs('activity.*') ? 'active' : '' }}">
                        <i class="bi bi-activity"></i>
                        Activity Log
                    </a>
                </div>
            </div>
            @endif
        </div>

        <div class="nav-user">
            <div class="user-dropdown">
                <div class="user-btn">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">{{ auth()->user()->role }}</div>
                    </div>
                    <i class="bi bi-chevron-down" style="color: var(--gray-400); font-size: 0.75rem;"></i>
                </div>
                <div class="user-dropdown-menu">
                    <a href="{{ route('profile.index') }}" class="nav-dropdown-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <i class="bi bi-person"></i>
                        Profil Saya
                    </a>
                    <a href="{{ route('password.change') }}" class="nav-dropdown-item">
                        <i class="bi bi-key"></i>
                        Ubah Password
                    </a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-dropdown-item" style="width: 100%; border: none; background: none; cursor: pointer; color: var(--danger);">
                            <i class="bi bi-box-arrow-right"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay" onclick="closeMobileMenu()"></div>

    <!-- Main Content -->
    <main class="main-wrapper">
        <div class="content-wrapper">
            @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-error">
                <i class="bi bi-exclamation-circle"></i>
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        function toggleMobileMenu() {
            const navMenu = document.getElementById('navMenu');
            const overlay = document.getElementById('mobileOverlay');
            navMenu.classList.toggle('active');
            overlay.classList.toggle('active');
            document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
        }

        function closeMobileMenu() {
            document.getElementById('navMenu').classList.remove('active');
            document.getElementById('mobileOverlay').classList.remove('active');
            document.body.style.overflow = '';
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.nav-menu') && !e.target.closest('.mobile-toggle')) {
                closeMobileMenu();
            }
        });

        // Close mobile menu on window resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024) {
                closeMobileMenu();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
