<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\KategoriSarpras;
use App\Models\Sarpras;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // =============================================
        // SEED USERS (3 AKUN DEMO)
        // =============================================
        
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@sarpras.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Petugas
        User::create([
            'name' => 'Petugas Sarpras',
            'email' => 'petugas@sarpras.test',
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);

        // Pengguna
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'pengguna@sarpras.test',
            'password' => Hash::make('password'),
            'role' => 'pengguna',
        ]);

        // Tambah beberapa pengguna lagi untuk demo
        User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti@sarpras.test',
            'password' => Hash::make('password'),
            'role' => 'pengguna',
        ]);

        User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad@sarpras.test',
            'password' => Hash::make('password'),
            'role' => 'pengguna',
        ]);

        // =============================================
        // SEED KATEGORI SARPRAS
        // =============================================

        $kategori = [
            ['nama' => 'Perangkat TIK', 'deskripsi' => 'Komputer, laptop, proyektor, dan perangkat IT lainnya'],
            ['nama' => 'Alat Laboratorium', 'deskripsi' => 'Alat-alat untuk praktikum lab IPA, Fisika, Kimia'],
            ['nama' => 'Buku & Referensi', 'deskripsi' => 'Buku pelajaran, referensi, dan koleksi perpustakaan'],
            ['nama' => 'Furniture', 'deskripsi' => 'Meja, kursi, lemari, dan perlengkapan ruangan'],
            ['nama' => 'Alat Olahraga', 'deskripsi' => 'Bola, raket, matras, dan perlengkapan olahraga'],
            ['nama' => 'Peralatan Audio Visual', 'deskripsi' => 'Sound system, microphone, speaker, layar proyektor'],
        ];

        foreach ($kategori as $k) {
            KategoriSarpras::create($k);
        }

        // =============================================
        // SEED SARPRAS (DATA SAMPLE)
        // =============================================

        $sarpras = [
            // Perangkat TIK
            ['kode' => 'TIK-001', 'nama' => 'Proyektor Epson EB-X51', 'kategori_id' => 1, 'lokasi' => 'Lab RPL', 'jumlah_stok' => 3, 'kondisi' => 'baik'],
            ['kode' => 'TIK-002', 'nama' => 'Laptop ASUS VivoBook', 'kategori_id' => 1, 'lokasi' => 'Lab Komputer', 'jumlah_stok' => 10, 'kondisi' => 'baik'],
            ['kode' => 'TIK-003', 'nama' => 'Printer Canon G2010', 'kategori_id' => 1, 'lokasi' => 'Ruang TU', 'jumlah_stok' => 2, 'kondisi' => 'baik'],
            ['kode' => 'TIK-004', 'nama' => 'Kamera DSLR Canon 750D', 'kategori_id' => 1, 'lokasi' => 'Ruang Multimedia', 'jumlah_stok' => 2, 'kondisi' => 'baik'],
            ['kode' => 'TIK-005', 'nama' => 'Router WiFi TP-Link', 'kategori_id' => 1, 'lokasi' => 'Lab Jaringan', 'jumlah_stok' => 5, 'kondisi' => 'rusak_ringan'],
            
            // Alat Laboratorium
            ['kode' => 'LAB-001', 'nama' => 'Multimeter Digital', 'kategori_id' => 2, 'lokasi' => 'Lab Elektronika', 'jumlah_stok' => 15, 'kondisi' => 'baik'],
            ['kode' => 'LAB-002', 'nama' => 'Oscilloscope', 'kategori_id' => 2, 'lokasi' => 'Lab Elektronika', 'jumlah_stok' => 3, 'kondisi' => 'baik'],
            ['kode' => 'LAB-003', 'nama' => 'Mikroskop Biologi', 'kategori_id' => 2, 'lokasi' => 'Lab IPA', 'jumlah_stok' => 10, 'kondisi' => 'baik'],
            
            // Buku & Referensi
            ['kode' => 'BK-001', 'nama' => 'Buku Pemrograman PHP', 'kategori_id' => 3, 'lokasi' => 'Perpustakaan', 'jumlah_stok' => 5, 'kondisi' => 'baik'],
            ['kode' => 'BK-002', 'nama' => 'Buku Jaringan Komputer', 'kategori_id' => 3, 'lokasi' => 'Perpustakaan', 'jumlah_stok' => 8, 'kondisi' => 'baik'],
            ['kode' => 'BK-003', 'nama' => 'Buku Matematika SMK', 'kategori_id' => 3, 'lokasi' => 'Perpustakaan', 'jumlah_stok' => 20, 'kondisi' => 'rusak_ringan'],
            
            // Furniture
            ['kode' => 'FN-001', 'nama' => 'Meja Rapat Besar', 'kategori_id' => 4, 'lokasi' => 'Ruang Rapat', 'jumlah_stok' => 2, 'kondisi' => 'baik'],
            ['kode' => 'FN-002', 'nama' => 'Kursi Lipat', 'kategori_id' => 4, 'lokasi' => 'Gudang', 'jumlah_stok' => 50, 'kondisi' => 'baik'],
            
            // Alat Olahraga
            ['kode' => 'OR-001', 'nama' => 'Bola Basket Molten', 'kategori_id' => 5, 'lokasi' => 'Gudang Olahraga', 'jumlah_stok' => 10, 'kondisi' => 'baik'],
            ['kode' => 'OR-002', 'nama' => 'Raket Badminton Yonex', 'kategori_id' => 5, 'lokasi' => 'Gudang Olahraga', 'jumlah_stok' => 8, 'kondisi' => 'rusak_ringan'],
            
            // Audio Visual
            ['kode' => 'AV-001', 'nama' => 'Sound System Portable', 'kategori_id' => 6, 'lokasi' => 'Ruang OSIS', 'jumlah_stok' => 2, 'kondisi' => 'baik'],
            ['kode' => 'AV-002', 'nama' => 'Wireless Microphone', 'kategori_id' => 6, 'lokasi' => 'Ruang Multimedia', 'jumlah_stok' => 4, 'kondisi' => 'baik'],
            ['kode' => 'AV-003', 'nama' => 'Layar Proyektor Tripod', 'kategori_id' => 6, 'lokasi' => 'Gudang', 'jumlah_stok' => 3, 'kondisi' => 'rusak_berat'],
        ];

        foreach ($sarpras as $s) {
            Sarpras::create($s);
        }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('');
        $this->command->info('📧 Akun Demo:');
        $this->command->info('   Admin   : admin@sarpras.test / password');
        $this->command->info('   Petugas : petugas@sarpras.test / password');
        $this->command->info('   Pengguna: pengguna@sarpras.test / password');
    }
}
