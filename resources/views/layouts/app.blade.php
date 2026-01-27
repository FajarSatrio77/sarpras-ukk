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
            background: linear-gradient(135deg, #f0f4ff 0%, #f5f0ff 50%, #fff5f7 100%);
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--gray-700);
            position: relative;
        }

        /* Subtle grid pattern overlay */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(99, 102, 241, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(168, 85, 247, 0.03) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        /* Top Navigation */
        .top-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--nav-height);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 255, 0.98));
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(99, 102, 241, 0.12);
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 0 24px;
            box-shadow: 0 4px 30px rgba(99, 102, 241, 0.12), 0 2px 8px rgba(0, 0, 0, 0.04);
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
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(99, 102, 241, 0.06), 0 1px 2px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.8);
            position: relative;
            z-index: 1;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), #a855f7, var(--info));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .card:hover::before {
            opacity: 1;
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(99, 102, 241, 0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(180deg, rgba(248, 250, 255, 0.5), transparent);
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
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 4px 24px rgba(99, 102, 241, 0.06);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.8);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.08), transparent 70%);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(99, 102, 241, 0.15);
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
            border-spacing: 0;
        }

        .table th, .table td {
            padding: 14px 20px;
            text-align: left;
            border-bottom: 1px solid rgba(99, 102, 241, 0.06);
        }

        .table th {
            font-weight: 600;
            color: var(--gray-600);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: linear-gradient(180deg, rgba(248, 250, 255, 0.8), rgba(241, 245, 249, 0.5));
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:nth-child(even) {
            background: rgba(99, 102, 241, 0.015);
        }

        .table tbody tr:hover {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.04), rgba(168, 85, 247, 0.02));
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

        /* Forms */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--gray-700);
            font-size: 0.875rem;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--gray-300);
            border-radius: 12px;
            font-family: inherit;
            font-size: 0.9rem;
            color: var(--gray-800);
            background-color: white;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .form-control::placeholder {
            color: var(--gray-400);
        }

        .form-control.is-invalid, .form-select.is-invalid {
            border-color: var(--danger);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23ef4444'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linecap='round' d='M5.8 3.6h.4L6 6.5zM6 8.2af.6.6 0 110-1.2.6.6 0 010 1.2z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px 16px;
            padding-right: 40px;
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 6px;
            font-size: 0.8rem;
            color: var(--danger);
        }

        .required {
            color: var(--danger);
            margin-left: 2px;
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
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
                padding: 16px;
                gap: 4px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                overflow-y: auto;
                z-index: 999;
                align-items: stretch;
                box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            }

            .nav-menu.active {
                transform: translateX(0);
            }

            .mobile-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .nav-item {
                width: 100%;
                justify-content: flex-start;
                padding: 14px 16px;
                font-size: 1rem;
            }

            .nav-dropdown {
                width: 100%;
            }

            /* Dropdown menu hidden by default on mobile */
            .nav-dropdown-menu {
                position: static;
                opacity: 0;
                visibility: hidden;
                max-height: 0;
                overflow: hidden;
                transform: none;
                box-shadow: none;
                border: none;
                padding: 0;
                background: var(--gray-50);
                border-radius: 12px;
                margin-top: 0;
                transition: all 0.3s ease;
            }

            /* Show dropdown when parent has .open class */
            .nav-dropdown.open .nav-dropdown-menu {
                opacity: 1;
                visibility: visible;
                max-height: 500px;
                padding: 8px;
                padding-left: 20px;
                margin-top: 8px;
            }

            .nav-dropdown .nav-item::after {
                content: '▼';
                font-size: 0.6rem;
                margin-left: auto;
                transition: transform 0.3s ease;
            }

            .nav-dropdown.open .nav-item::after {
                transform: rotate(180deg);
            }

            .nav-dropdown-item {
                padding: 12px 14px;
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

            /* Mobile menu close area */
            .mobile-menu-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding-bottom: 12px;
                margin-bottom: 8px;
                border-bottom: 1px solid var(--gray-200);
            }

            .mobile-menu-title {
                font-weight: 600;
                color: var(--dark);
            }

            .mobile-menu-close {
                padding: 8px;
                background: var(--gray-100);
                border: none;
                border-radius: 8px;
                cursor: pointer;
                font-size: 1.2rem;
                color: var(--gray-600);
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

        /* Table responsive wrapper */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        @media (max-width: 768px) {
            .table-responsive {
                margin: 0 -16px;
                padding: 0 16px;
                width: calc(100% + 32px);
            }

            .table {
                min-width: 600px;
            }

            /* Form improvements for mobile */
            .form-group {
                margin-bottom: 16px;
            }

            .form-control,
            .form-select,
            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="number"],
            input[type="date"],
            input[type="file"],
            textarea,
            select {
                font-size: 16px !important; /* Prevents iOS zoom */
                padding: 12px 14px;
            }

            /* Better card header on mobile */
            .card-header {
                padding: 14px 16px;
                flex-wrap: wrap;
            }

            .card-title {
                font-size: 0.95rem;
            }

            /* Fix alerts for mobile */
            .alert {
                padding: 12px 14px;
                font-size: 0.85rem;
                flex-wrap: wrap;
            }

            /* Footer buttons stack on mobile */
            .btn-group,
            .action-buttons {
                flex-direction: column;
                gap: 8px;
            }

            .action-buttons .btn {
                width: 100%;
            }

            /* Modal improvements */
            .modal-content {
                margin: 10px;
                max-height: 90vh;
                overflow-y: auto;
            }

            /* Page titles */
            .page-title {
                font-size: 1.25rem;
            }

            /* Better spacing */
            .mb-4 {
                margin-bottom: 16px !important;
            }

            .mb-3 {
                margin-bottom: 12px !important;
            }
        }

        /* Ultra small phones (320px) */
        @media (max-width: 360px) {
            .top-nav {
                padding: 0 8px;
            }

            .nav-brand img {
                width: 36px;
                height: 36px;
            }

            .mobile-toggle {
                padding: 8px 10px;
                font-size: 1.3rem;
            }

            .user-avatar {
                width: 28px;
                height: 28px;
                font-size: 0.7rem;
            }

            .content-wrapper {
                padding: 8px;
            }

            .card-body {
                padding: 12px;
            }

            .stat-card {
                padding: 12px;
            }

            .btn {
                padding: 10px 12px;
                font-size: 0.8rem;
            }
        }
    </style>
    @stack('styles')
<style>
    /* Sidebar Navigation Modern */
    :root {
        --sidebar-width: 260px;
        --header-height: 60px;
    }

    body {
        background-color: #f3f4f6;
    }

    /* Layout Structure */
    .app-container {
        display: flex;
        min-height: 100vh;
    }

    /* Sidebar Styles */
    .sidebar {
        width: var(--sidebar-width);
        background: white;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        border-right: 1px solid rgba(0,0,0,0.05);
        box-shadow: 4px 0 24px rgba(0,0,0,0.02);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sidebar-header {
        height: 70px;
        display: flex;
        align-items: center;
        padding: 0 24px;
        border-bottom: 1px solid rgba(0,0,0,0.03);
    }

    .brand-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
    }

    .brand-logo img {
        height: 32px;
        width: auto;
    }

    .brand-text {
        font-weight: 700;
        font-size: 1.25rem;
        color: var(--primary);
        letter-spacing: -0.5px;
    }

    .sidebar-content {
        flex: 1;
        overflow-y: auto;
        padding: 24px 16px;
    }

    .menu-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--gray-400);
        margin: 0 0 12px 12px;
        letter-spacing: 0.5px;
    }

    .nav-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        color: var(--gray-600);
        text-decoration: none;
        border-radius: 12px;
        margin-bottom: 4px;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .nav-item:hover {
        background: var(--gray-50);
        color: var(--primary);
        transform: translateX(4px);
    }

    .nav-item.active {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.3);
    }

    .nav-item i {
        font-size: 1.25rem;
        width: 24px;
        text-align: center;
    }

    .sidebar-footer {
        padding: 20px;
        border-top: 1px solid rgba(0,0,0,0.03);
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: var(--gray-50);
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .user-profile:hover {
        background: var(--gray-100);
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: var(--primary);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .user-info {
        flex: 1;
        min-width: 0;
    }

    .user-name {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--dark);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-role {
        font-size: 0.75rem;
        color: var(--secondary);
    }

    /* Main Content Area */
    .main-wrapper {
        flex: 1;
        margin-left: var(--sidebar-width);
        min-height: 100vh;
        width: calc(100% - var(--sidebar-width));
        display: flex;
        flex-direction: column;
    }

    .top-header {
        display: none; /* Desktop uses sidebar only */
    }

    .content-wrapper {
        padding: 0 25px 25px 25px; /* Top padding removed completely */
        max-width: 1400px;
        margin: 0 auto;
        width: 100%;
    }

    /* Mobile Responsive */
    @media (max-width: 1024px) {
        body {
            padding-bottom: 80px; /* Space for bottom nav */
        }

        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .main-wrapper {
            margin-left: 0;
            width: 100%;
            padding-top: var(--header-height);
        }

        .top-header {
            display: flex;
            align-items: center;
            justify-content: center; /* Center logo */
            height: var(--header-height);
            background: white;
            padding: 0 20px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 990;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .mobile-toggle {
            display: none; /* Hide hamburger, use bottom nav menu */
        }

        .content-wrapper {
            padding: 20px 16px;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.4);
            z-index: 995;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            backdrop-filter: blur(2px);
        }

        .overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Bottom Navigation */
        .bottom-nav {
            display: flex;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            height: 70px;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.08); /* Softer shadow */
            z-index: 1000;
            padding: 0 16px;
            align-items: center;
            justify-content: space-around;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            border-top: 1px solid rgba(0,0,0,0.02);
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: var(--gray-400);
            font-size: 0.75rem;
            gap: 4px;
            padding: 8px;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 60px;
        }

        .bottom-nav-item i {
            font-size: 1.4rem;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .bottom-nav-item.active {
            color: var(--primary);
        }

        .bottom-nav-item.active i {
            transform: translateY(-2px);
        }

        /* Floating Scale Effect for center button (Scan) */
        .bottom-nav-item.scan-btn {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            margin-bottom: 35px; /* Float above bar */
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
            border: 4px solid #fff; /* White ring */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bottom-nav-item.scan-btn i {
            font-size: 1.6rem;
        }
    }
    
    @media (min-width: 1025px) {
        .bottom-nav {
            display: none;
        }
    }
</style>
</head>
<body>
    <!-- Mobile Overlay -->
    <div class="overlay" id="mobileOverlay" onclick="toggleSidebar()"></div>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="{{ auth()->user()->isPengguna() ? route('peminjaman.daftar') : route('dashboard') }}" 
           class="bottom-nav-item {{ request()->routeIs('dashboard', 'peminjaman.daftar') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i>
            <span>Beranda</span>
        </a>
        
        <a href="{{ route('pengembalian.scan') }}" class="bottom-nav-item scan-btn">
            <i class="bi bi-qr-code-scan"></i>
        </a>
        
        <a href="#" class="bottom-nav-item" onclick="toggleSidebar(); return false;">
            <i class="bi bi-list"></i>
            <span>Menu</span>
        </a>
    </nav>

    <!-- Sidebar Navigation -->
    <aside class="sidebar" id="sidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="brand-logo">
                <img src="{{ asset('images/logosmea.png') }}" alt="Logo">
                <span class="brand-text">SARPRASKITA</span>
            </a>
        </div>

        <!-- Sidebar Content -->
        <div class="sidebar-content">
            @if(auth()->user()->canManage())
            <div class="menu-label">Main Menu</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i>
                <span>Dashboard</span>
            </a>
            @endif

            @if(auth()->user()->isPengguna())
            <div class="menu-label">Peminjaman</div>
            <a href="{{ route('peminjaman.daftar') }}" class="nav-item {{ request()->routeIs('peminjaman.daftar', 'peminjaman.create') ? 'active' : '' }}">
                <i class="bi bi-cart-plus"></i>
                <span>Ajukan Peminjaman</span>
            </a>
            <a href="{{ route('peminjaman.riwayat') }}" class="nav-item {{ request()->routeIs('peminjaman.riwayat') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                <span>Riwayat Saya</span>
            </a>
            @endif

            @if(auth()->user()->canManage())
            <div class="menu-label">Transaksi</div>
            <a href="{{ route('peminjaman.index') }}" class="nav-item {{ request()->routeIs('peminjaman.index', 'peminjaman.show') ? 'active' : '' }}">
                <i class="bi bi-clipboard-check"></i>
                <span>Data Peminjaman</span>
            </a>
            <a href="{{ route('pengembalian.scan') }}" class="nav-item {{ request()->routeIs('pengembalian.*') ? 'active' : '' }}">
                <i class="bi bi-qr-code-scan"></i>
                <span>Scan Pengembalian</span>
            </a>
            @endif

            <div class="menu-label">Layanan</div>
            @if(auth()->user()->isPengguna())
            <a href="{{ route('pengaduan.create') }}" class="nav-item {{ request()->routeIs('pengaduan.create') ? 'active' : '' }}">
                <i class="bi bi-megaphone"></i>
                <span>Buat Pengaduan</span>
            </a>
            <a href="{{ route('pengaduan.index') }}" class="nav-item {{ request()->routeIs('pengaduan.index', 'pengaduan.show') ? 'active' : '' }}">
                <i class="bi bi-list-ul"></i>
                <span>Riwayat Pengaduan</span>
            </a>
            @endif
            @if(auth()->user()->canManage())
            <a href="{{ route('pengaduan.index') }}" class="nav-item {{ request()->routeIs('pengaduan.index', 'pengaduan.show') ? 'active' : '' }}">
                <i class="bi bi-list-ul"></i>
                <span>Data Pengaduan</span>
            </a>
            @endif

            @if(auth()->user()->canManage())
            <div class="menu-label">Kelola Data</div>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Kelola User</span>
            </a>
            @endif
            <a href="{{ route('sarpras.index') }}" class="nav-item {{ request()->routeIs('sarpras.*') ? 'active' : '' }}">
                <i class="bi bi-box"></i>
                <span>Data Barang</span>
            </a>
            <a href="{{ route('kategori.index') }}" class="nav-item {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                <i class="bi bi-folder"></i>
                <span>Kategori</span>
            </a>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('checklist.index') }}" class="nav-item {{ request()->routeIs('checklist.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-check"></i>
                <span>Template Checklist</span>
            </a>
            @endif
            @endif

            @if(auth()->user()->isAdmin())
            <div class="menu-label">Laporan</div>
            <a href="{{ route('laporan.asset-health') }}" class="nav-item {{ request()->routeIs('laporan.asset-health') ? 'active' : '' }}">
                <i class="bi bi-heart-pulse"></i>
                <span>Asset Health</span>
            </a>
            <a href="{{ route('laporan.damage-analytics') }}" class="nav-item {{ request()->routeIs('laporan.damage-analytics') ? 'active' : '' }}">
                <i class="bi bi-graph-down-arrow"></i>
                <span>Damage Analytics</span>
            </a>
            <a href="{{ route('laporan.kerusakan') }}" class="nav-item {{ request()->routeIs('laporan.kerusakan') ? 'active' : '' }}">
                <i class="bi bi-exclamation-octagon"></i>
                <span>Lap. Kerusakan</span>
            </a>
            <a href="{{ route('activity.index') }}" class="nav-item {{ request()->routeIs('activity.*') ? 'active' : '' }}">
                <i class="bi bi-activity"></i>
                <span>Activity Log</span>
            </a>
            @endif
        </div>

        <!-- Sidebar Footer (User Profile) -->
        <div class="sidebar-footer">
            <a href="{{ route('profile.index') }}" class="user-profile">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
                {{-- <i class="bi bi-chevron-right" style="font-size: 0.8rem; color: var(--gray-400);"></i> --}}
            </a>
            <div style="margin-top: 12px; display: flex; gap: 8px;">
                <form action="{{ route('logout') }}" method="POST" style="flex: 1;">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="width: 100%; border-color: var(--gray-200); color: var(--danger); font-size: 0.8rem; padding: 8px;">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- App Main Container -->
    <div class="main-wrapper">
        <!-- Mobile Header (Visible only on mobile) -->
        <header class="top-header">
            <div class="brand-logo">
                <img src="{{ asset('images/logosmea.png') }}" alt="Logo">
                <span class="brand-text">SARPRAS</span>
            </div>
            <!-- Hamburger Removed (Now in Bottom Nav) -->
        </header>

        <!-- Main Content -->
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
    </div>

    <!-- Script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            
            // Prevent body scroll when menu is open on mobile
            if (window.innerWidth <= 1024) {
                document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
            }
        }

        // Close sidebar when resizing to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024) {
                document.getElementById('sidebar').classList.remove('active');
                document.getElementById('mobileOverlay').classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
