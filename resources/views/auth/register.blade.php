<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun - SARPRAS SMK</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .signup-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        .signup-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            color: white;
            text-align: center;
        }

        .signup-left h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .signup-left p {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 400px;
            line-height: 1.7;
        }

        .signup-icon {
            font-size: 5rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .signup-right {
            width: 520px;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 50px 60px;
            box-shadow: -10px 0 40px rgba(0,0,0,0.1);
            overflow-y: auto;
        }

        .signup-header {
            margin-bottom: 30px;
        }

        .signup-header h2 {
            font-size: 1.75rem;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .signup-header p {
            color: #64748b;
        }

        .form-group {
            margin-bottom: 20px;
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
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .btn-signup {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: inherit;
            margin-top: 10px;
        }

        .btn-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .error-message ul {
            margin: 0;
            padding-left: 20px;
        }

        .login-link {
            text-align: center;
            margin-top: 24px;
            color: #64748b;
            font-size: 0.9rem;
        }

        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .password-hint {
            font-size: 0.8rem;
            color: #94a3b8;
            margin-top: 6px;
        }

        @media (max-width: 900px) {
            .signup-left {
                display: none;
            }
            .signup-right {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="signup-left">
            <i class="bi bi-person-plus signup-icon"></i>
            <h1>Bergabung Sekarang!</h1>
            <p>Daftarkan akun Anda untuk dapat mengajukan peminjaman sarana prasarana dan melaporkan pengaduan di sekolah.</p>
        </div>
        
        <div class="signup-right">
            <div class="signup-header">
                <h2>Buat Akun Baru 🎉</h2>
                <p>Isi form berikut untuk mendaftar</p>
            </div>

            @if($errors->any())
            <div class="error-message">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <div class="input-icon-wrapper">
                        <i class="bi bi-person"></i>
                        <input type="text" name="name" class="form-input @error('name') error @enderror" 
                               value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="input-icon-wrapper">
                        <i class="bi bi-envelope"></i>
                        <input type="email" name="email" class="form-input @error('email') error @enderror" 
                               value="{{ old('email') }}" placeholder="nama@email.com" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-lock"></i>
                            <input type="password" name="password" class="form-input @error('password') error @enderror" 
                                   placeholder="Min. 8 karakter" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-lock-fill"></i>
                            <input type="password" name="password_confirmation" class="form-input" 
                                   placeholder="Ulangi password" required>
                        </div>
                    </div>
                </div>
                <p class="password-hint">
                    <i class="bi bi-info-circle"></i> Password minimal 8 karakter
                </p>

                <button type="submit" class="btn-signup">
                    <i class="bi bi-person-plus"></i> Daftar Sekarang
                </button>
            </form>

            <div class="login-link">
                Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
            </div>
        </div>
    </div>
</body>
</html>
