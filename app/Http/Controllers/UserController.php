<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Daftar semua user
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        // Validate sort column
        $allowedSorts = ['name', 'email', 'nisn', 'role', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        
        // Validate sort order
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $users = $query->orderBy($sortBy, $sortOrder)->paginate(15);

        // Statistik
        $statistik = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'petugas' => User::where('role', 'petugas')->count(),
            'pengguna' => User::where('role', 'pengguna')->count(),
        ];

        return view('users.index', compact('users', 'statistik'));
    }

    /**
     * Form tambah user baru
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Simpan user baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nisn' => 'required|string|max:20|unique:users,nisn',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,petugas,pengguna',
        ], [
            'name.required' => 'Nama wajib diisi',
            'nisn.required' => 'NISN wajib diisi',
            'nisn.unique' => 'NISN sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role wajib dipilih',
        ]);

        // Auto-generate email dari NISN
        $email = strtolower($request->nisn) . '@smkn1boyolangu.sch.id';

        User::create([
            'name' => $request->name,
            'nisn' => $request->nisn,
            'email' => $email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Detail user
     */
    public function show(User $user)
    {
        // Load statistik aktivitas user
        $user->load(['peminjaman' => function($q) {
            $q->latest()->take(5);
        }]);

        $statistik = [
            'total_peminjaman' => $user->peminjaman()->count(),
            'aktif' => $user->peminjaman()->where('status', 'dipinjam')->count(),
            'selesai' => $user->peminjaman()->where('status', 'dikembalikan')->count(),
        ];

        return view('users.show', compact('user', 'statistik'));
    }

    /**
     * Form edit user
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,petugas,pengguna',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role wajib dipilih',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui');
    }

    /**
     * Hapus user
     */
    public function destroy(User $user)
    {
        // Jangan hapus diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri');
        }

        // Cek apakah user memiliki peminjaman aktif
        if ($user->peminjaman()->whereIn('status', ['menunggu', 'disetujui', 'dipinjam'])->exists()) {
            return back()->with('error', 'User tidak bisa dihapus karena masih memiliki peminjaman aktif');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus');
    }
}
