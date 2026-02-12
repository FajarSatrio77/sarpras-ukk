<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aktivasi Akun - SARPRAS SMK NEGERI 1 BOYOLANGU</title>
    
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
            padding-right: 48px;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            left: auto !important;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            transition: color 0.3s ease;
            z-index: 10;
            padding: 5px;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        .btn-activate {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: inherit;
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.4);
        }

        .btn-activate:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(34, 197, 94, 0.5);
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

        .info-box {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(99, 102, 241, 0.1));
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            border-left: 4px solid var(--secondary);
        }

        .info-box h4 {
            font-size: 0.9rem;
            color: var(--secondary);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-box p {
            font-size: 0.85rem;
            color: #64748b;
            line-height: 1.6;
        }

        .login-link {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e2e8f0;
        }

        .login-link a {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
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
                <h2>Aktivasi Akun 🔐</h2>
                <p>Aktivasi akun untuk mulai menggunakan sistem</p>
            </div>

            <div class="info-box">
                <h4><i class="bi bi-info-circle"></i> Informasi Penting</h4>
                <p>Jika ini pertama kalinya Anda menggunakan sistem SARPRAS, silakan aktivasi akun Anda dengan memasukkan NISN/NIP dan password yang telah diberikan oleh admin.</p>
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
                <form method="POST" action="{{ route('activate') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">NISN/NIP</label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-person-badge"></i>
                            <input type="text" name="nisn" class="form-input @error('nisn') error @enderror" 
                                   value="{{ old('nisn', session('nisn')) }}" placeholder="Masukkan NISN atau NIP" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-lock"></i>
                            <input type="password" name="password" id="password" class="form-input" 
                                   placeholder="Masukkan password" required>
                            <i class="bi bi-eye password-toggle" id="togglePassword"></i>
                        </div>
                    </div>

                    <script>
                        document.getElementById('togglePassword')?.addEventListener('click', function (e) {
                            const passwordInput = document.getElementById('password');
                            const icon = this;
                            
                            if (passwordInput.type === 'password') {
                                passwordInput.type = 'text';
                                icon.classList.remove('bi-eye');
                                icon.classList.add('bi-eye-slash');
                            } else {
                                passwordInput.type = 'password';
                                icon.classList.remove('bi-eye-slash');
                                icon.classList.add('bi-eye');
                            }
                        });
                    </script>

                    <button type="submit" class="btn-activate">
                        <i class="bi bi-check-circle"></i> Aktivasi Akun
                    </button>
                </form>

                <div class="login-link">
                    <p>Sudah aktivasi? <a href="{{ route('login') }}">Kembali ke Login</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
