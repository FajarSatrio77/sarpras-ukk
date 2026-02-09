pe---
description: Repository Information Overview
alwaysApply: true
---

# UKK-SARPRAS Information

## Summary
**UKK-SARPRAS** is a web-based Facilities and Infrastructure (Sarana dan Prasarana) management system built with the **Laravel** framework. The application is designed to manage school assets, handle item borrowing processes, track maintenance, and manage complaints. It features a role-based access control system (Admin, Petugas, Guru, and Pengguna).

## Structure
- [./app/](./app/): Contains the core logic, including **Models** (User, Sarpras, Peminjaman, etc.), **Controllers**, and **Middleware**.
- [./routes/](./routes/): Defines the application routes, primarily in [./routes/web.php](./routes/web.php).
- [./database/](./database/): Contains **Migrations**, **Seeders**, and **Factories** for database schema and data.
- [./resources/](./resources/): Holds frontend assets, including **Blade** templates, **CSS** (Tailwind), and **JS** (Vite).
- [./public/](./public/): The public directory containing the main entry point [./public/index.php](./public/index.php).
- [./tests/](./tests/): Contains **PHPUnit** feature and unit tests.

## Language & Runtime
**Language**: PHP, JavaScript  
**Version**: PHP ^8.1, Laravel ^10.0  
**Build System**: Vite  
**Package Manager**: Composer (PHP), npm (JS)

## Dependencies
**Main Dependencies**:
- **laravel/framework**: Core web framework.
- **laravel/sanctum**: API authentication.
- **guzzlehttp/guzzle**: HTTP client.
- **tailwindcss**: Utility-first CSS framework.
- **daisyui**: Tailwind CSS components.

**Development Dependencies**:
- **phpunit/phpunit**: Testing framework.
- **laravel/sail**: Dockerized development environment.
- **laravel/pint**: PHP code style fixer.
- **vite**: Frontend build tool.

## Build & Installation
```bash
# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Build assets
npm run build
```

## Testing
**Framework**: PHPUnit  
**Test Location**: [./tests/](./tests/)  
**Naming Convention**: `*Test.php`  
**Configuration**: [./phpunit.xml](./phpunit.xml)

**Run Command**:
```bash
php artisan test
```
