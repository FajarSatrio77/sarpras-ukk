@extends('layouts.app')

@section('title', 'Ubah Password')

@section('content')
<div style="max-width: 500px;">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                <i class="bi bi-key" style="margin-right: 8px;"></i>
                Ubah Password
            </h5>
        </div>
        <div class="card-body">
            @if($errors->any())
            <div class="alert alert-error">
                <i class="bi bi-exclamation-circle"></i>
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Password Saat Ini
                    </label>
                    <input type="password" name="current_password" 
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                           placeholder="Masukkan password saat ini" required>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Password Baru
                    </label>
                    <input type="password" name="password" 
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                           placeholder="Masukkan password baru (min. 8 karakter)" required>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Konfirmasi Password Baru
                    </label>
                    <input type="password" name="password_confirmation" 
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                           placeholder="Ulangi password baru" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="bi bi-check-lg"></i>
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
