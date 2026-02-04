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
            --primary: #1e40af;
            --primary-dark: #1e3a8a;
            --primary-light: #3b82f6;
            --primary-rgb: 30, 64, 175;
            --secondary: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --purple: #8b5cf6;
            --pink: #ec4899;
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
            --nav-height: 64px;
            --sidebar-bg: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f0f4ff 0%, #faf5ff 50%, #fff7ed 100%);
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--gray-700);
            position: relative;
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
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
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
            background: rgba(30, 64, 175, 0.1);
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
            background: linear-gradient(135deg, #1e40af, #3b82f6);
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
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid var(--gray-200);
            position: relative;
            z-index: 1;
            transition: box-shadow 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
        }

        .card-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--gray-800);
        }

        .card-body {
            padding: 20px;
        }

        /* Stat Cards */
        .stat-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transition: all 0.2s ease;
            border: 1px solid rgba(0, 0, 0, 0.04);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.12);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .stat-icon.primary { 
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
        }
        .stat-icon.success { 
            background: linear-gradient(135deg, #10b981, #34d399);
            color: white;
        }
        .stat-icon.warning { 
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            color: white;
        }
        .stat-icon.danger { 
            background: linear-gradient(135deg, #ef4444, #f87171);
            color: white;
        }
        .stat-icon.info { 
            background: linear-gradient(135deg, #06b6d4, #22d3ee);
            color: white;
        }

        .stat-content h3 {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--gray-800);
            line-height: 1;
        }

        .stat-content p {
            color: var(--gray-500);
            font-size: 0.8rem;
            margin-top: 4px;
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
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid var(--gray-100);
        }

        .table th {
            font-weight: 600;
            color: var(--gray-500);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: var(--gray-50);
        }

        .table tbody tr {
            transition: background 0.15s ease;
        }

        .table tbody tr:hover {
            background: var(--gray-50);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            gap: 4px;
        }

        .badge-success { background: rgba(5, 150, 105, 0.12); color: var(--success); }
        .badge-warning { background: rgba(217, 119, 6, 0.12); color: var(--warning); }
        .badge-danger { background: rgba(220, 38, 38, 0.12); color: var(--danger); }
        .badge-info { background: rgba(8, 145, 178, 0.12); color: var(--info); }
        .badge-primary { background: rgba(79, 110, 247, 0.12); color: var(--primary); }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
            font-size: 0.85rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-outline {
            background: white;
            border: 1px solid var(--gray-200);
            color: var(--gray-600);
        }

        .btn-outline:hover {
            border-color: var(--gray-300);
            background: var(--gray-50);
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
            padding: 10px 14px;
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.875rem;
            color: var(--gray-800);
            background-color: white;
            transition: border-color 0.15s ease;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 110, 247, 0.1);
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
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.875rem;
        }

        .alert-success {
            background: rgba(5, 150, 105, 0.08);
            color: #047857;
            border: 1px solid rgba(5, 150, 105, 0.15);
        }

        .alert-error {
            background: rgba(220, 38, 38, 0.08);
            color: #b91c1c;
            border: 1px solid rgba(220, 38, 38, 0.15);
        }

        /* Pagination */
        nav[role="navigation"] {
            display: flex;
            justify-content: center;
        }

        nav[role="navigation"] > div {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        nav[role="navigation"] > div > div:first-child {
            display: none;
        }

        nav[role="navigation"] span[aria-current="page"] span,
        nav[role="navigation"] a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease;
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
            border-color: var(--gray-300);
        }

        nav[role="navigation"] svg {
            width: 14px !important;
            height: 14px !important;
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

        /* ========================================
           GLOBAL LOADING & ANIMATION STYLES 
        ======================================== */
        
        /* Keyframe Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        /* Page Loader - Optimized Modern Style */
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.98);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 12px;
            z-index: 99999;
            opacity: 1;
            visibility: visible;
            transition: opacity 0.2s ease-out, visibility 0.2s ease-out;
            will-change: opacity, visibility;
        }
        
        .page-loader.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        
        /* Simple Elegant Spinner */
        .loader-spinner {
            width: 32px;
            height: 32px;
            border: 2px solid #f0f0f0;
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spinSmooth 0.6s linear infinite;
            will-change: transform;
        }
        
        @keyframes spinSmooth {
            to { transform: rotate(360deg); }
        }
        
        /* Pulse Dot Alternative */
        .loader-pulse {
            width: 10px;
            height: 10px;
            background: var(--primary);
            border-radius: 50%;
            animation: pulseDot 1s ease-in-out infinite;
        }
        
        @keyframes pulseDot {
            0%, 100% { 
                transform: scale(1);
                opacity: 1;
            }
            50% { 
                transform: scale(1.5);
                opacity: 0.5;
            }
        }
        
        .loader-text {
            font-size: 0.7rem;
            color: #9ca3af;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        
        /* Skeleton Loading */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 8px;
        }
        
        .skeleton-text {
            height: 16px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        
        .skeleton-title {
            height: 24px;
            width: 60%;
            margin-bottom: 16px;
        }
        
        /* Animated Cards */
        .card-animated {
            opacity: 0;
            animation: fadeInUp 0.5s ease forwards;
        }
        
        .card-animated:nth-child(1) { animation-delay: 0.05s; }
        .card-animated:nth-child(2) { animation-delay: 0.1s; }
        .card-animated:nth-child(3) { animation-delay: 0.15s; }
        .card-animated:nth-child(4) { animation-delay: 0.2s; }
        .card-animated:nth-child(5) { animation-delay: 0.25s; }
        .card-animated:nth-child(6) { animation-delay: 0.3s; }
        
        /* Stat Card Animations */
        .stat-card-animated {
            opacity: 0;
            animation: fadeInUp 0.5s ease forwards;
        }
        
        .stat-card-animated:nth-child(1) { animation-delay: 0.05s; }
        .stat-card-animated:nth-child(2) { animation-delay: 0.1s; }
        .stat-card-animated:nth-child(3) { animation-delay: 0.15s; }
        .stat-card-animated:nth-child(4) { animation-delay: 0.2s; }
        
        /* Slide In Animations */
        .slide-in-right {
            opacity: 0;
            animation: slideInRight 0.5s ease forwards;
        }
        
        .slide-in-left {
            opacity: 0;
            animation: slideInLeft 0.5s ease forwards;
        }
        
        .slide-in-right:nth-child(1), .slide-in-left:nth-child(1) { animation-delay: 0.1s; }
        .slide-in-right:nth-child(2), .slide-in-left:nth-child(2) { animation-delay: 0.2s; }
        .slide-in-right:nth-child(3), .slide-in-left:nth-child(3) { animation-delay: 0.3s; }
        
        /* Table Row Animations */
        .table-animated tbody tr {
            opacity: 0;
            animation: fadeInUp 0.4s ease forwards;
        }
        
        .table-animated tbody tr:nth-child(1) { animation-delay: 0.05s; }
        .table-animated tbody tr:nth-child(2) { animation-delay: 0.08s; }
        .table-animated tbody tr:nth-child(3) { animation-delay: 0.11s; }
        .table-animated tbody tr:nth-child(4) { animation-delay: 0.14s; }
        .table-animated tbody tr:nth-child(5) { animation-delay: 0.17s; }
        .table-animated tbody tr:nth-child(6) { animation-delay: 0.20s; }
        .table-animated tbody tr:nth-child(7) { animation-delay: 0.23s; }
        .table-animated tbody tr:nth-child(8) { animation-delay: 0.26s; }
        .table-animated tbody tr:nth-child(9) { animation-delay: 0.29s; }
        .table-animated tbody tr:nth-child(10) { animation-delay: 0.32s; }
        
        /* Content Fade In */
        .content-fade {
            opacity: 0;
            animation: fadeInUp 0.5s ease 0.1s forwards;
        }
        
        /* Button Loading State */
        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.8;
        }
        
        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid transparent;
            border-top-color: currentColor;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        
        .btn-loading span, .btn-loading i {
            opacity: 0;
        }
        
        /* Image Loading */
        .img-loading {
            position: relative;
            overflow: hidden;
            min-height: 100px;
        }
        
        .img-loading::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: inherit;
        }
        
        .img-loading img {
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        
        .img-loading.loaded::before {
            display: none;
        }
        
        .img-loading.loaded img {
            opacity: 1;
        }
        
        /* Card Hover Enhancement */
        .card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }
        
        /* Alert Animations */
        .alert {
            animation: slideInRight 0.4s ease;
        }
        
        /* ========================================
           GLOBAL POPUP NOTIFICATION SYSTEM
           ======================================== */
        .notification-popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .notification-popup-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        
        .notification-popup {
            background: white;
            border-radius: 20px;
            padding: 32px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            transform: scale(0.8) translateY(30px);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.15);
        }
        
        .notification-popup-overlay.show .notification-popup {
            transform: scale(1) translateY(0);
        }
        
        .notification-popup .popup-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 32px;
        }
        
        .notification-popup .popup-icon.success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #059669;
        }
        
        .notification-popup .popup-icon.error {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #dc2626;
        }
        
        .notification-popup .popup-icon.warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #d97706;
        }
        
        .notification-popup .popup-icon.info {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #2563eb;
        }
        
        .notification-popup .popup-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }
        
        .notification-popup .popup-message {
            font-size: 0.95rem;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        
        .notification-popup .popup-btn {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            border: none;
            padding: 14px 40px;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s;
            box-shadow: 0 4px 15px rgba(30, 64, 175, 0.35);
        }
        
        .notification-popup .popup-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(30, 64, 175, 0.45);
        }
        
        .notification-popup .popup-btn.success {
            background: linear-gradient(135deg, #059669, #10b981);
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.35);
        }
        
        .notification-popup .popup-btn.success:hover {
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.45);
        }
        
        /* ========================================
           GLOBAL CONFIRM POPUP SYSTEM - PREMIUM
           ======================================== */
        .confirm-popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.7), rgba(30, 41, 59, 0.8));
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .confirm-popup-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        
        .confirm-popup {
            background: linear-gradient(145deg, #ffffff, #f8fafc);
            border-radius: 24px;
            padding: 40px 36px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            transform: scale(0.7) translateY(50px);
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 
                0 32px 64px rgba(0, 0, 0, 0.25),
                0 16px 32px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            position: relative;
            overflow: hidden;
        }
        
        .confirm-popup::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light), #8b5cf6);
            border-radius: 24px 24px 0 0;
        }
        
        .confirm-popup-overlay.show .confirm-popup {
            transform: scale(1) translateY(0);
        }
        
        .confirm-popup .popup-icon {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 40px;
            background: linear-gradient(135deg, #fef3c7, #fcd34d);
            color: #b45309;
            box-shadow: 
                0 8px 24px rgba(251, 191, 36, 0.4),
                inset 0 -2px 4px rgba(0, 0, 0, 0.1),
                inset 0 2px 4px rgba(255, 255, 255, 0.9);
            animation: iconPulse 2s ease-in-out infinite;
            position: relative;
        }
        
        .confirm-popup .popup-icon::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 2px solid rgba(251, 191, 36, 0.3);
            animation: iconRing 2s ease-in-out infinite;
        }
        
        @keyframes iconPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes iconRing {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.2; }
        }
        
        .confirm-popup .popup-icon.danger {
            background: linear-gradient(135deg, #fee2e2, #fca5a5);
            color: #b91c1c;
            box-shadow: 
                0 8px 24px rgba(239, 68, 68, 0.4),
                inset 0 -2px 4px rgba(0, 0, 0, 0.1),
                inset 0 2px 4px rgba(255, 255, 255, 0.9);
        }
        
        .confirm-popup .popup-icon.danger::after {
            border-color: rgba(239, 68, 68, 0.3);
        }
        
        .confirm-popup .popup-icon.success {
            background: linear-gradient(135deg, #d1fae5, #6ee7b7);
            color: #047857;
            box-shadow: 
                0 8px 24px rgba(16, 185, 129, 0.4),
                inset 0 -2px 4px rgba(0, 0, 0, 0.1),
                inset 0 2px 4px rgba(255, 255, 255, 0.9);
        }
        
        .confirm-popup .popup-icon.success::after {
            border-color: rgba(16, 185, 129, 0.3);
        }
        
        .confirm-popup .popup-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 12px;
            letter-spacing: -0.02em;
        }
        
        .confirm-popup .popup-message {
            font-size: 1rem;
            color: #64748b;
            line-height: 1.7;
            margin-bottom: 32px;
        }
        
        .confirm-popup .popup-message strong {
            color: #334155;
            font-weight: 600;
        }
        
        .confirm-popup .popup-buttons {
            display: flex;
            gap: 14px;
            justify-content: center;
        }
        
        .confirm-popup .popup-btn {
            padding: 14px 32px;
            border-radius: 14px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .confirm-popup .popup-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .confirm-popup .popup-btn:hover::before {
            left: 100%;
        }
        
        .confirm-popup .popup-btn.cancel {
            background: linear-gradient(145deg, #f1f5f9, #e2e8f0);
            color: #475569;
            box-shadow: 
                0 2px 8px rgba(0, 0, 0, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
        }
        
        .confirm-popup .popup-btn.cancel:hover {
            background: linear-gradient(145deg, #e2e8f0, #cbd5e1);
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }
        
        .confirm-popup .popup-btn.confirm {
            background: linear-gradient(135deg, var(--primary), #3b82f6);
            color: white;
            box-shadow: 
                0 4px 16px rgba(30, 64, 175, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }
        
        .confirm-popup .popup-btn.confirm:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 24px rgba(30, 64, 175, 0.5);
        }
        
        .confirm-popup .popup-btn.confirm:active {
            transform: translateY(-1px) scale(0.98);
        }
        
        .confirm-popup .popup-btn.confirm.danger {
            background: linear-gradient(135deg, #dc2626, #f43f5e);
            box-shadow: 0 4px 16px rgba(220, 38, 38, 0.4);
        }
        
        .confirm-popup .popup-btn.confirm.danger:hover {
            box-shadow: 0 8px 24px rgba(220, 38, 38, 0.5);
        }
        
        .confirm-popup .popup-btn.confirm.success {
            background: linear-gradient(135deg, #059669, #10b981);
            box-shadow: 0 4px 16px rgba(5, 150, 105, 0.4);
        }
        
        .confirm-popup .popup-btn.confirm.success:hover {
            box-shadow: 0 8px 24px rgba(5, 150, 105, 0.5);
        }
        
        /* Mobile optimization */
        @media (max-width: 480px) {
            .confirm-popup {
                padding: 32px 24px;
                margin: 16px;
            }
            .confirm-popup .popup-icon {
                width: 72px;
                height: 72px;
                font-size: 32px;
            }
            .confirm-popup .popup-buttons {
                flex-direction: column;
            }
            .confirm-popup .popup-btn {
                width: 100%;
            }
        }
        /* Page Title Animation */
        .page-title-animated {
            opacity: 0;
            animation: fadeInUp 0.5s ease forwards;
        }
        
        /* Info Grid Animation */
        .info-grid > div {
            opacity: 0;
            animation: fadeInUp 0.4s ease forwards;
        }
        
        .info-grid > div:nth-child(1) { animation-delay: 0.1s; }
        .info-grid > div:nth-child(2) { animation-delay: 0.15s; }
        .info-grid > div:nth-child(3) { animation-delay: 0.2s; }
        .info-grid > div:nth-child(4) { animation-delay: 0.25s; }
        .info-grid > div:nth-child(5) { animation-delay: 0.3s; }
        .info-grid > div:nth-child(6) { animation-delay: 0.35s; }
        
        /* Badge Bounce */
        .badge-animated {
            animation: bounceIn 0.5s ease;
        }
        
        /* Reduce animations for users who prefer reduced motion */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
            
            .page-loader {
                display: none !important;
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
        
        border-right: 1px solid var(--gray-100);
        transition: transform 0.25s ease;
    }

    .sidebar-header {
        height: 70px;
        display: flex;
        align-items: center;
        padding: 0 20px;
        border-bottom: 1px solid var(--gray-100);
        background: linear-gradient(135deg, rgba(30, 64, 175, 0.03), rgba(59, 130, 246, 0.03));
    }

    .brand-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
    }

    .brand-logo img {
        height: 40px;
        width: auto;
    }

    .brand-text {
        font-weight: 700;
        font-size: 1.15rem;
        background: linear-gradient(135deg, #1e40af, #3b82f6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .sidebar-content {
        flex: 1;
        overflow-y: auto;
        padding: 16px 12px;
    }

    .menu-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--gray-400);
        margin: 20px 0 8px 14px;
        letter-spacing: 0.06em;
    }

    .menu-label:first-child {
        margin-top: 4px;
    }

    .nav-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 14px;
        color: var(--gray-600);
        text-decoration: none;
        border-radius: 10px;
        margin-bottom: 2px;
        transition: all 0.15s ease;
        font-weight: 500;
        font-size: 0.875rem;
        position: relative;
    }

    .nav-item:hover {
        background: var(--gray-50);
        color: var(--primary);
    }

    .nav-item.active {
        background: linear-gradient(135deg, rgba(30, 64, 175, 0.1), rgba(59, 130, 246, 0.08));
        color: var(--primary);
        font-weight: 600;
    }

    .nav-item.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 24px;
        background: linear-gradient(180deg, #1e40af, #3b82f6);
        border-radius: 0 4px 4px 0;
    }

    .nav-item i {
        font-size: 1.15rem;
        width: 22px;
        text-align: center;
    }

    .sidebar-footer {
        padding: 16px;
        border-top: 1px solid var(--gray-100);
        background: linear-gradient(180deg, transparent, rgba(30, 64, 175, 0.02));
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: var(--gray-50);
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.15s ease;
        border: 1px solid var(--gray-100);
    }

    .user-profile:hover {
        background: white;
        border-color: var(--gray-200);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: white;
        font-size: 0.9rem;
    }

    .user-info {
        flex: 1;
        min-width: 0;
    }

    .user-name {
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--gray-800);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-role {
        font-size: 0.72rem;
        color: var(--gray-500);
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
        <a href="{{ auth()->user()->isPeminjam() ? route('peminjaman.daftar') : route('dashboard') }}" 
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

            @if(auth()->user()->isPeminjam())
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
                <span>Kategori Barang</span>
            </a>

            @endif

            @if(auth()->user()->canManage())
            <div class="menu-label">Transaksi</div>
            <a href="{{ route('peminjaman.index') }}" class="nav-item {{ request()->routeIs('peminjaman.index', 'peminjaman.show') ? 'active' : '' }}">
                <i class="bi bi-clipboard-check"></i>
                <span>Kelola Peminjaman</span>
            </a>
            <a href="{{ route('pengembalian.scan') }}" class="nav-item {{ request()->routeIs('pengembalian.*') ? 'active' : '' }}">
                <i class="bi bi-qr-code-scan"></i>
                <span>Scan Pengembalian</span>
            </a>
            @endif

            <div class="menu-label">Layanan</div>
            @if(auth()->user()->isPeminjam())
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


            @if(auth()->user()->canManage())
            <div class="menu-label">Sampah</div>
            <a href="{{ route('trash.index') }}" class="nav-item {{ request()->routeIs('trash.index') ? 'active' : '' }}">
                <i class="bi bi-trash3"></i>
                <span>Sampah</span>
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

    <!-- Global Page Loader -->
    <div class="page-loader" id="globalPageLoader">
        <div class="loader-spinner"></div>
    </div>

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
            @yield('content')
        </div>
    </div>
    
    <!-- Global Notification Popup -->
    <div class="notification-popup-overlay" id="globalNotificationPopup">
        <div class="notification-popup">
            <div class="popup-icon success" id="notifPopupIcon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="popup-title" id="notifPopupTitle">Berhasil</div>
            <div class="popup-message" id="notifPopupMessage">Operasi berhasil dilakukan.</div>
            <button class="popup-btn success" id="notifPopupBtn" onclick="closeNotificationPopup()">OK</button>
        </div>
    </div>
    
    <!-- Global Confirm Popup -->
    <div class="confirm-popup-overlay" id="globalConfirmPopup">
        <div class="confirm-popup">
            <div class="popup-icon" id="confirmPopupIcon">
                <i class="bi bi-question-circle-fill"></i>
            </div>
            <div class="popup-title" id="confirmPopupTitle">Konfirmasi</div>
            <div class="popup-message" id="confirmPopupMessage">Apakah Anda yakin?</div>
            <div class="popup-buttons">
                <button class="popup-btn cancel" onclick="closeConfirmPopup(false)">Batal</button>
                <button class="popup-btn confirm" id="confirmPopupBtn" onclick="closeConfirmPopup(true)">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>
    
    @if(session('success') || session('error') || session('warning') || session('info'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
            showNotification('Berhasil!', '{{ session('success') }}', 'success');
            @endif
            @if(session('error'))
            showNotification('Terjadi Kesalahan', '{{ session('error') }}', 'error');
            @endif
            @if(session('warning'))
            showNotification('Perhatian', '{{ session('warning') }}', 'warning');
            @endif
            @if(session('info'))
            showNotification('Informasi', '{{ session('info') }}', 'info');
            @endif
        });
    </script>
    @endif

    <!-- Script -->
    <script>
        // ========================================
        // GLOBAL NOTIFICATION POPUP FUNCTIONS
        // ========================================
        function showNotification(title, message, type = 'success') {
            const overlay = document.getElementById('globalNotificationPopup');
            const icon = document.getElementById('notifPopupIcon');
            const titleEl = document.getElementById('notifPopupTitle');
            const messageEl = document.getElementById('notifPopupMessage');
            const btn = document.getElementById('notifPopupBtn');
            
            titleEl.textContent = title;
            messageEl.innerHTML = message;
            
            // Reset classes
            icon.className = 'popup-icon ' + type;
            btn.className = 'popup-btn ' + type;
            
            // Set icon based on type
            const icons = {
                success: '<i class="bi bi-check-circle-fill"></i>',
                error: '<i class="bi bi-x-circle-fill"></i>',
                warning: '<i class="bi bi-exclamation-triangle-fill"></i>',
                info: '<i class="bi bi-info-circle-fill"></i>'
            };
            icon.innerHTML = icons[type] || icons.success;
            
            overlay.classList.add('show');
            
            overlay.onclick = function(e) {
                if (e.target === overlay) {
                    closeNotificationPopup();
                }
            };
        }
        
        function closeNotificationPopup() {
            document.getElementById('globalNotificationPopup').classList.remove('show');
        }
        
        // ========================================
        // GLOBAL CONFIRM POPUP FUNCTIONS
        // ========================================
        let confirmCallback = null;
        let confirmForm = null;
        
        function showConfirm(title, message, options = {}) {
            const overlay = document.getElementById('globalConfirmPopup');
            const icon = document.getElementById('confirmPopupIcon');
            const titleEl = document.getElementById('confirmPopupTitle');
            const messageEl = document.getElementById('confirmPopupMessage');
            const confirmBtn = document.getElementById('confirmPopupBtn');
            
            titleEl.textContent = title;
            messageEl.innerHTML = message;
            
            // Set type (danger, success, or default warning)
            const type = options.type || 'warning';
            const isDanger = type === 'danger';
            const isSuccess = type === 'success';
            
            icon.className = 'popup-icon' + (isDanger ? ' danger' : isSuccess ? ' success' : '');
            icon.innerHTML = isDanger ? '<i class="bi bi-exclamation-triangle-fill"></i>' : 
                            isSuccess ? '<i class="bi bi-check-circle-fill"></i>' :
                            '<i class="bi bi-question-circle-fill"></i>';
            
            confirmBtn.className = 'popup-btn confirm' + (isDanger ? ' danger' : isSuccess ? ' success' : '');
            confirmBtn.textContent = options.confirmText || 'Ya, Lanjutkan';
            
            confirmCallback = options.onConfirm || null;
            confirmForm = options.form || null;
            
            overlay.classList.add('show');
        }
        
        function closeConfirmPopup(confirmed) {
            document.getElementById('globalConfirmPopup').classList.remove('show');
            
            if (confirmed) {
                if (confirmForm) {
                    confirmForm.submit();
                } else if (confirmCallback) {
                    confirmCallback();
                }
            }
            
            confirmCallback = null;
            confirmForm = null;
        }
        
        // Auto-initialize confirm popups for forms and buttons
        document.addEventListener('DOMContentLoaded', function() {
            // Handle forms with data-confirm attribute
            document.querySelectorAll('form[data-confirm]').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const message = this.dataset.confirm;
                    const type = this.dataset.confirmType || 'warning';
                    const title = this.dataset.confirmTitle || 'Konfirmasi';
                    showConfirm(title, message, { 
                        type: type, 
                        form: this,
                        confirmText: this.dataset.confirmBtn || 'Ya, Lanjutkan'
                    });
                });
            });
            
            // Handle buttons/links with data-confirm attribute
            document.querySelectorAll('[data-confirm]:not(form)').forEach(el => {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    const message = this.dataset.confirm;
                    const type = this.dataset.confirmType || 'warning';
                    const title = this.dataset.confirmTitle || 'Konfirmasi';
                    const href = this.href;
                    showConfirm(title, message, {
                        type: type,
                        confirmText: this.dataset.confirmBtn || 'Ya, Lanjutkan',
                        onConfirm: () => { if (href) window.location.href = href; }
                    });
                });
            });
        });
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeNotificationPopup();
            }
        });
        
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
        
        // ========================================
        // GLOBAL LOADING EFFECTS
        // ========================================
        
        document.addEventListener('DOMContentLoaded', function() {
            // Hide page loader when DOM is ready
            const pageLoader = document.getElementById('globalPageLoader');
            if (pageLoader) {
                setTimeout(() => {
                    pageLoader.classList.add('hidden');
                }, 200);
            }
            
            // Button loading state on form submit
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.classList.contains('btn-no-loading')) {
                        submitBtn.classList.add('btn-loading');
                    }
                });
            });
            
            // Auto-add loaded class to images when they finish loading
            document.querySelectorAll('.img-loading img').forEach(img => {
                if (img.complete) {
                    img.parentElement.classList.add('loaded');
                } else {
                    img.addEventListener('load', function() {
                        this.parentElement.classList.add('loaded');
                    });
                }
            });
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href');
                    if (targetId !== '#') {
                        e.preventDefault();
                        const target = document.querySelector(targetId);
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }
                });
            });
        });
        
        // Show page loader on page navigate (for turbo-like feel)
        window.addEventListener('beforeunload', function() {
            const pageLoader = document.getElementById('globalPageLoader');
            if (pageLoader) {
                pageLoader.classList.remove('hidden');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
