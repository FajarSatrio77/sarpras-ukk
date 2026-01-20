<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SARPRAS SMK NEGERI 1 BOYOLANGU</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #22c55e;
            --primary-dark: #16a34a;
            --secondary: #3b82f6;
            --dark: #1e293b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #f8fafc;
        }

        .login-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        .login-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            background: #f8fafc;
            text-align: center;
        }

        .login-left h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .login-left h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            opacity: 0.95;
        }

        .login-left p {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 400px;
            line-height: 1.7;
        }

        .login-logo {
            width: 150px;
            height: 150px;
            margin-bottom: 24px;
            object-fit: contain;
        }

        .login-box {
            text-align: center;
            max-width: 400px;
        }

        .login-box h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--primary);
        }

        .login-box h2 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--secondary);
        }

        .login-box p {
            font-size: 1rem;
            color: #64748b;
            line-height: 1.7;
        }

        .login-right {
            width: 550px;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 50px;
            box-shadow: -20px 0 60px rgba(0, 0, 0, 0.15);
        }

        .login-header {
            margin-bottom: 40px;
        }

        .login-header h2 {
            font-size: 1.75rem;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .login-header p {
            color: #64748b;
        }

        .login-form-card {
            background: transparent;
            padding: 0;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: inherit;
            background: #f8fafc;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.15);
            background: white;
        }

        .form-input.error {
            border-color: #ef4444;
        }

        .input-icon-wrapper {
            position: relative;
        }

        .input-icon-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .input-icon-wrapper .form-input {
            padding-left: 48px;
        }

        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-checkbox input {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
        }

        .form-checkbox label {
            color: #64748b;
            font-size: 0.9rem;
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #1e3a8a 0%, #7c3aed 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: inherit;
            box-shadow: 0 4px 15px rgba(124, 58, 237, 0.4);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(124, 58, 237, 0.5);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(34, 197, 94, 0.4);
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .success-message {
            background: rgba(34, 197, 94, 0.1);
            color: #166534;
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .demo-accounts {
            margin-top: 30px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
        }

        .demo-accounts h4 {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .demo-account {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 0.85rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .demo-account:last-child {
            border-bottom: none;
        }

        .demo-account span:first-child {
            color: var(--dark);
            font-weight: 500;
        }

        .demo-account span:last-child {
            color: #64748b;
        }

        @media (max-width: 900px) {
            .login-left {
                display: none;
            }
            .login-right {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <div class="login-box">
                <img src="{{ asset('images/logo-smkn1boyolangu.jpeg') }}" alt="Logo SMKN 1 Boyolangu" class="login-logo">
                <h1>SARPRAS</h1>
                <h2>SMKN 1 BOYOLANGU</h2>
                <p>Sistem Manajemen Peminjaman dan Pengaduan Sarana Prasarana Sekolah Berbasis Web</p>
            </div>
        </div>
        
        <div class="login-right">
            <div class="login-header">
                <h2>Selamat Datang! 👋</h2>
                <p>Silakan login untuk melanjutkan</p>
            </div>

            @if($errors->any())
            <div class="error-message">
                <i class="bi bi-exclamation-circle"></i>
                {{ $errors->first() }}
            </div>
            @endif

            @if(session('success'))
            <div class="success-message">
                <i class="bi bi-check-circle"></i>
                {{ session('success') }}
            </div>
            @endif

            <div class="login-form-card">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">NISN</label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-person-badge"></i>
                            <input type="text" name="nisn" class="form-input @error('nisn') error @enderror" 
                                   value="{{ old('nisn') }}" placeholder="Masukkan NISN" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-lock"></i>
                            <input type="password" name="password" class="form-input" 
                                   placeholder="Masukkan password" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-checkbox">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">Ingat saya</label>
                        </div>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
