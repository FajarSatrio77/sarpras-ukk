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

            <form method="POST" action="{{ route('password.update') }}" onsubmit="return confirmSubmit(this, 'Yakin ingin mengubah password?')">
                @csrf
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Password Saat Ini
                    </label>
                    <div class="input-icon-wrapper">
                        <input type="password" name="current_password" id="cp-password-lama"
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                               placeholder="Masukkan password saat ini" required>
                        <i class="bi bi-eye password-toggle"></i>
                    </div>
                    <div id="cp-password-lama-warning" style="display: none; margin-top: 8px; padding: 8px 12px; border-radius: 8px; font-size: 0.85rem; align-items: center; gap: 6px;">
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Password Baru
                    </label>
                    <div class="input-icon-wrapper">
                        <input type="password" name="password" id="cp-password-baru"
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                               placeholder="Masukkan password baru (min. 8 karakter)" required>
                        <i class="bi bi-eye password-toggle"></i>
                    </div>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Konfirmasi Password Baru
                    </label>
                    <div class="input-icon-wrapper">
                        <input type="password" name="password_confirmation" id="cp-password-konfirmasi"
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                               placeholder="Ulangi password baru" required>
                        <i class="bi bi-eye password-toggle"></i>
                    </div>
                    <div id="cp-password-warning" style="display: none; margin-top: 8px; padding: 8px 12px; border-radius: 8px; font-size: 0.85rem; align-items: center; gap: 6px;">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" id="cp-btn-submit" style="width: 100%;">
                    <i class="bi bi-check-lg"></i>
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordLama = document.getElementById('cp-password-lama');
    const passwordBaru = document.getElementById('cp-password-baru');
    const passwordKonfirmasi = document.getElementById('cp-password-konfirmasi');
    const warningLama = document.getElementById('cp-password-lama-warning');
    const warningKonfirmasi = document.getElementById('cp-password-warning');
    const btnSubmit = document.getElementById('cp-btn-submit');

    let currentPasswordValid = false;
    let confirmPasswordValid = false;
    let checkTimer = null;

    function updateSubmitButton() {
        const allValid = currentPasswordValid && confirmPasswordValid;
        btnSubmit.disabled = !allValid;
        btnSubmit.style.opacity = allValid ? '1' : '0.5';
    }

    // Cek password lama via AJAX dengan debounce
    passwordLama.addEventListener('input', function() {
        clearTimeout(checkTimer);
        const val = this.value;

        if (val.length === 0) {
            warningLama.style.display = 'none';
            passwordLama.style.borderColor = '#e2e8f0';
            currentPasswordValid = false;
            updateSubmitButton();
            return;
        }

        // Tampilkan loading
        warningLama.style.display = 'flex';
        warningLama.style.background = 'rgba(148, 163, 184, 0.1)';
        warningLama.style.color = '#64748b';
        warningLama.innerHTML = '<i class="bi bi-hourglass-split"></i> Memeriksa password...';
        passwordLama.style.borderColor = '#e2e8f0';

        checkTimer = setTimeout(function() {
            fetch("{{ route('password.check-current') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ current_password: val })
            })
            .then(response => response.json())
            .then(data => {
                if (data.match) {
                    warningLama.style.display = 'flex';
                    warningLama.style.background = 'rgba(34, 197, 94, 0.1)';
                    warningLama.style.color = '#16a34a';
                    warningLama.innerHTML = '<i class="bi bi-check-circle-fill"></i> Password saat ini benar';
                    passwordLama.style.borderColor = '#16a34a';
                    currentPasswordValid = true;
                } else {
                    warningLama.style.display = 'flex';
                    warningLama.style.background = 'rgba(239, 68, 68, 0.1)';
                    warningLama.style.color = '#dc2626';
                    warningLama.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> Password saat ini salah';
                    passwordLama.style.borderColor = '#dc2626';
                    currentPasswordValid = false;
                }
                updateSubmitButton();
            })
            .catch(() => {
                warningLama.style.display = 'none';
                passwordLama.style.borderColor = '#e2e8f0';
                currentPasswordValid = false;
                updateSubmitButton();
            });
        }, 500);
    });

    // Validasi konfirmasi password
    function validatePasswordMatch() {
        const pw = passwordBaru.value;
        const conf = passwordKonfirmasi.value;

        if (conf.length === 0) {
            warningKonfirmasi.style.display = 'none';
            passwordKonfirmasi.style.borderColor = '#e2e8f0';
            confirmPasswordValid = false;
            updateSubmitButton();
            return;
        }

        if (pw !== conf) {
            warningKonfirmasi.style.display = 'flex';
            warningKonfirmasi.style.background = 'rgba(239, 68, 68, 0.1)';
            warningKonfirmasi.style.color = '#dc2626';
            warningKonfirmasi.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> Password baru dan konfirmasi password tidak sama';
            passwordKonfirmasi.style.borderColor = '#dc2626';
            confirmPasswordValid = false;
        } else {
            warningKonfirmasi.style.display = 'flex';
            warningKonfirmasi.style.background = 'rgba(34, 197, 94, 0.1)';
            warningKonfirmasi.style.color = '#16a34a';
            warningKonfirmasi.innerHTML = '<i class="bi bi-check-circle-fill"></i> Password cocok';
            passwordKonfirmasi.style.borderColor = '#16a34a';
            confirmPasswordValid = true;
        }
        updateSubmitButton();
    }

    passwordBaru.addEventListener('input', validatePasswordMatch);
    passwordKonfirmasi.addEventListener('input', validatePasswordMatch);

    // Disable submit awal sampai validasi terpenuhi
    btnSubmit.disabled = true;
    btnSubmit.style.opacity = '0.5';
});
</script>
@endpush
