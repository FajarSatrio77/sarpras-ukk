<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SARPRAS SMK</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #64748b;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --dark: #1e293b;
            --light: #f8fafc;
            --sidebar-width: 260px;
            --header-height: 65px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--dark) 0%, #0f172a 100%);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-logo {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .sidebar-logo i {
            font-size: 2rem;
            color: var(--primary);
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-label {
            padding: 10px 20px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.4);
            font-weight: 600;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            gap: 12px;
        }

        .menu-item:hover, .menu-item.active {
            background: rgba(255,255,255,0.05);
            color: white;
            border-left-color: var(--primary);
        }

        .menu-item i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        /* Header */
        .header {
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left h4 {
            color: var(--dark);
            font-weight: 600;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            background: var(--light);
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-dropdown:hover {
            background: #e2e8f0;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            color: var(--dark);
            font-size: 0.9rem;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--secondary);
            text-transform: capitalize;
        }

        /* Content Area */
        .content-wrapper {
            padding: 30px;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .card-header {
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--dark);
        }

        .card-body {
            padding: 20px;
        }

        /* Stat Cards */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-icon.primary { background: rgba(99, 102, 241, 0.1); color: var(--primary); }
        .stat-icon.success { background: rgba(34, 197, 94, 0.1); color: var(--success); }
        .stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .stat-icon.danger { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
        .stat-icon.info { background: rgba(59, 130, 246, 0.1); color: var(--info); }

        .stat-content h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark);
        }

        .stat-content p {
            color: var(--secondary);
            font-size: 0.875rem;
            margin-top: 4px;
        }

        /* Grid */
        .grid {
            display: grid;
            gap: 24px;
        }

        .grid-4 { grid-template-columns: repeat(4, 1fr); }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-2 { grid-template-columns: repeat(2, 1fr); }

        @media (max-width: 1200px) {
            .grid-4 { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .grid-4, .grid-3, .grid-2 { grid-template-columns: 1fr; }
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
        }

        /* Table */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .table th {
            font-weight: 600;
            color: var(--secondary);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tr:hover {
            background: var(--light);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success { background: rgba(34, 197, 94, 0.1); color: var(--success); }
        .badge-warning { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .badge-danger { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
        .badge-info { background: rgba(59, 130, 246, 0.1); color: var(--info); }
        .badge-primary { background: rgba(99, 102, 241, 0.1); color: var(--primary); }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid #e2e8f0;
            color: var(--secondary);
        }

        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Alerts */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #166534;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #991b1b;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* Dropdown */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            min-width: 200px;
            padding: 8px;
            display: none;
            z-index: 1000;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            color: var(--dark);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background: var(--light);
        }

        .dropdown-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 8px 0;
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
            transition: all 0.3s ease;
        }

        nav[role="navigation"] span[aria-current="page"] span {
            background: var(--primary);
            color: white;
        }

        nav[role="navigation"] a {
            background: white;
            color: var(--secondary);
            border: 1px solid #e2e8f0;
        }

        nav[role="navigation"] a:hover {
            background: var(--light);
            border-color: var(--primary);
            color: var(--primary);
        }

        nav[role="navigation"] svg {
            width: 16px !important;
            height: 16px !important;
        }

        /* Simple pagination for older Laravel */
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 8px;
            justify-content: center;
        }

        .pagination li {
            display: inline-block;
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
            transition: all 0.3s ease;
            background: white;
            color: var(--secondary);
            border: 1px solid #e2e8f0;
        }

        .pagination li.active span {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .pagination li a:hover {
            background: var(--light);
            border-color: var(--primary);
            color: var(--primary);
        }

        .pagination li.disabled span {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pagination svg,
        .pagination i {
            width: 16px !important;
            height: 16px !important;
            font-size: 16px !important;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="sidebar-logo">
                <i class="bi bi-box-seam"></i>
                <span>SARPRAS</span>
            </a>
        </div>
        
        <nav class="sidebar-menu">
            <span class="menu-label">Menu Utama</span>
            
            <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i>
                <span>Dashboard</span>
            </a>

            @if(auth()->user()->canManage())
                <span class="menu-label">Kelola Data</span>
                
                @if(auth()->user()->isAdmin())
                <a href="{{ route('users.index') }}" class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Kelola User</span>
                </a>
                @endif
                
                <a href="{{ route('kategori.index') }}" class="menu-item {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                    <i class="bi bi-folder"></i>
                    <span>Kategori Sarpras</span>
                </a>
                
                <a href="{{ route('sarpras.index') }}" class="menu-item {{ request()->routeIs('sarpras.*') ? 'active' : '' }}">
                    <i class="bi bi-box"></i>
                    <span>Data Sarpras</span>
                </a>
            @endif

            <span class="menu-label">Transaksi</span>
            
            @if(auth()->user()->isPengguna())
            <a href="{{ route('peminjaman.daftar') }}" class="menu-item {{ request()->routeIs('peminjaman.daftar', 'peminjaman.create') ? 'active' : '' }}">
                <i class="bi bi-cart-plus"></i>
                <span>Ajukan Peminjaman</span>
            </a>
            <a href="{{ route('peminjaman.riwayat') }}" class="menu-item {{ request()->routeIs('peminjaman.riwayat') ? 'active' : '' }}">
                <i class="bi bi-clipboard-check"></i>
                <span>Riwayat Peminjaman</span>
            </a>
            @endif
            
            @if(auth()->user()->canManage())
            <a href="{{ route('peminjaman.index') }}" class="menu-item {{ request()->routeIs('peminjaman.index', 'peminjaman.show') ? 'active' : '' }}">
                <i class="bi bi-clipboard-check"></i>
                <span>Kelola Peminjaman</span>
            </a>
            
            <a href="{{ route('pengembalian.scan') }}" class="menu-item {{ request()->routeIs('pengembalian.*') ? 'active' : '' }}">
                <i class="bi bi-qr-code-scan"></i>
                <span>Proses Pengembalian</span>
            </a>
            @endif

            <span class="menu-label">Pengaduan</span>
            
            @if(auth()->user()->isPengguna())
            <a href="{{ route('pengaduan.create') }}" class="menu-item {{ request()->routeIs('pengaduan.create') ? 'active' : '' }}">
                <i class="bi bi-megaphone"></i>
                <span>Buat Pengaduan</span>
            </a>
            @endif
            
            <a href="{{ route('pengaduan.index') }}" class="menu-item {{ request()->routeIs('pengaduan.index', 'pengaduan.show') ? 'active' : '' }}">
                <i class="bi bi-chat-square-text"></i>
                <span>{{ auth()->user()->canManage() ? 'Kelola Pengaduan' : 'Riwayat Pengaduan' }}</span>
            </a>


            @if(auth()->user()->isAdmin())
            <span class="menu-label">Laporan</span>
            
            <a href="#" class="menu-item">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Laporan Peminjaman</span>
            </a>
            
            <a href="{{ route('laporan.kerusakan') }}" class="menu-item {{ request()->routeIs('laporan.kerusakan') ? 'active' : '' }}">
                <i class="bi bi-exclamation-octagon"></i>
                <span>Laporan Kerusakan</span>
            </a>
            
            <a href="#" class="menu-item">
                <i class="bi bi-heart-pulse"></i>
                <span>Asset Health</span>
            </a>
            
            <a href="#" class="menu-item">
                <i class="bi bi-activity"></i>
                <span>Activity Log</span>
            </a>
            @endif
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <h4>@yield('title', 'Dashboard')</h4>
            </div>
            <div class="header-right">
                <div class="dropdown">
                    <div class="user-dropdown" onclick="toggleDropdown()">
                        <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        <div class="user-info">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-role">{{ auth()->user()->role }}</div>
                        </div>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="dropdown-menu" id="userDropdown">
                        <a href="#" class="dropdown-item">
                            <i class="bi bi-person"></i>
                            <span>Profil Saya</span>
                        </a>
                        <a href="{{ route('password.change') }}" class="dropdown-item">
                            <i class="bi bi-key"></i>
                            <span>Ubah Password</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item" style="width: 100%; border: none; background: none; cursor: pointer;">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
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
        function toggleDropdown() {
            document.getElementById('userDropdown').classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                document.getElementById('userDropdown').classList.remove('show');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
