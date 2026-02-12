<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLogin()
    {
        if (Auth::check()) {
            // Redirect berdasarkan role
            if (Auth::user()->isPeminjam()) {
                return redirect()->route('peminjaman.daftar');
            }
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string',
            'password' => 'required',
        ], [
            'nisn.required' => 'NISN/NIP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Cari user berdasarkan NISN/NIP
        $user = User::where('nisn', $request->nisn)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'nisn' => 'NISN/NIP atau password salah.',
            ])->onlyInput('nisn');
        }

        // Cek apakah user (pengguna/guru) sudah diaktivasi
        if ($user->isPeminjam() && !$user->is_activated) {
            return back()->withErrors([
                'activation' => 'Akun Anda belum diaktivasi. Silakan aktivasi akun terlebih dahulu.',
            ])->onlyInput('nisn')->with('needs_activation', true)->with('nisn', $request->nisn);
        }

        // Login user
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        
        // Catat aktivitas login
        ActivityLog::log('login', 'User ' . Auth::user()->name . ' berhasil login', null, [
            'user' => Auth::user()->name,
            'role' => Auth::user()->role,
        ]);

        // Redirect berdasarkan role (guru dan pengguna ke daftar peminjaman)
        $redirectRoute = Auth::user()->isPeminjam() ? 'peminjaman.daftar' : 'dashboard';

        return redirect()->intended(route($redirectRoute))
            ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        // Catat aktivitas logout
        ActivityLog::log('logout', 'User ' . Auth::user()->name . ' logout', null, [
            'user' => Auth::user()->name,
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Tampilkan halaman aktivasi akun
     */
    public function showActivate()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.activate');
    }

    /**
     * Proses aktivasi akun
     */
    public function activate(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string',
            'password' => 'required',
        ], [
            'nisn.required' => 'NISN/NIP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Cari user berdasarkan NISN/NIP
        $user = User::where('nisn', $request->nisn)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'nisn' => 'NISN/NIP atau password salah.',
            ])->onlyInput('nisn');
        }

        // Cek apakah sudah diaktivasi
        if ($user->is_activated) {
            return redirect()->route('login')
                ->with('success', 'Akun Anda sudah aktif. Silakan login.');
        }

        // Aktivasi akun
        $user->update(['is_activated' => true]);

        // Catat aktivitas aktivasi
        ActivityLog::log('aktivasi_akun', 'User ' . $user->name . ' mengaktivasi akun', $user->id, [
            'user' => $user->name,
            'email' => $user->email,
        ]);

        return redirect()->route('login')
            ->with('success', 'Akun berhasil diaktivasi! Silakan login.');
    }

    /**
     * Tampilkan form ubah password
     */
    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    /**
     * Proses ubah password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak sesuai.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        ActivityLog::log('ubah_password', 'User ' . $user->name . ' mengubah password', null, [
            'password_lama' => $request->current_password,
            'password_baru' => $request->password,
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    /**
     * Cek password saat ini via AJAX
     */
    public function checkCurrentPassword(Request $request)
    {
        $request->validate(['current_password' => 'required']);

        $isMatch = Hash::check($request->current_password, Auth::user()->password);

        return response()->json(['match' => $isMatch]);
    }

    /**
     * Tampilkan halaman registrasi
     */
    public function showRegister()
    {
        if (Auth::check()) {
            // Redirect berdasarkan role
            if (Auth::user()->isPeminjam()) {
                return redirect()->route('peminjaman.daftar');
            }
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    /**
     * Proses registrasi user baru
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        // Buat user baru dengan role 'pengguna' (default) - belum diaktivasi
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pengguna',
            'is_activated' => false,
        ]);

        // Catat aktivitas registrasi
        ActivityLog::log('register', 'User baru mendaftar: ' . $user->name, $user->id, [
            'user' => $user->name,
            'email' => $user->email,
            'role' => 'pengguna',
        ]);

        // Redirect ke halaman aktivasi (bukan auto-login)
        return redirect()->route('activate')
            ->with('success', 'Pendaftaran berhasil! Silakan aktivasi akun Anda.');
    }
}

