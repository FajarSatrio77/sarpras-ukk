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
        // SEED USERS (AKUN DEMO DENGAN NISN)
        // =============================================
        
        // Admin
        User::create([
            'name' => 'Administrator',
            'nisn' => '1111111111',
            'email' => 'admin@sarpras.test',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Petugas
        User::create([
            'name' => 'Petugas Sarpras',
            'nisn' => '2222222222',
            'email' => 'petugas@sarpras.test',
            'password' => Hash::make('petugas123'),
            'role' => 'petugas',
        ]);

        // =============================================
        // SISWA XII RPL 1 (Password = NISN)
        // =============================================
        $siswa = [
            ['nisn' => '0078320930', 'name' => 'Abdul Hafizh Aziz', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0077584240', 'name' => 'Aditya Hermana Putra', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0075132987', 'name' => 'Ahmad Yoga Setiawan', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0073908882', 'name' => 'Aksel Delvino Radinka Pratama', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0079696120', 'name' => 'Aprilia Dwi Lestari', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0083395255', 'name' => 'Arka Pangestu Wibowo', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0081887843', 'name' => 'Binti Dzuriatus Sholihah', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0071734863', 'name' => 'Cahya Buana Indah', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0076875404', 'name' => 'Cahya Langit Atmawinata', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0079331565', 'name' => 'Chafid Nouval Putra', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0074303390', 'name' => 'Chaisya Dwi Septa Rahmadhani', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0075931489', 'name' => 'Cheyril Athiyya Devrilia', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0072159818', 'name' => 'Daffa Abiyyu Asyqar', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0078169533', 'name' => 'Daffa Fadillilah Nur Iskandar', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0071358932', 'name' => 'Dimas Setia Pratama', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0075840594', 'name' => 'Dion Farado', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0085852668', 'name' => 'Diva Ananda Kartika', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0076018561', 'name' => 'Diva Livia Purbasari', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0076451181', 'name' => 'Fahriz Alghifari', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0081984121', 'name' => 'Febriana Andra Sari', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0071628194', 'name' => 'Ilham Frido Bagaskara', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0071412781', 'name' => 'Indira Faza Rahmadhani', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0073470159', 'name' => 'Intania Cahya Kirani', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0071551815', 'name' => 'Irsyad Arif', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0076645687', 'name' => 'Jovian Helga Kumara', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0075497995', 'name' => 'Kevin Juliano Arvarean', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0079715820', 'name' => 'Khairunnizam', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0072761653', 'name' => 'Luvita Anggraini', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0074801967', 'name' => 'Moch Dany Maulana', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0079611907', 'name' => 'Moh Fajar Satrio Utomo', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0081347058', 'name' => 'Muhammad Andrean Alfarizki', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0072242351', 'name' => 'Muhammad Fahri Irvandi', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0073286738', 'name' => 'Muhammad Firzatullah Aqila Risfayadi', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0083900604', 'name' => 'Muhammad Habib Al Kindy', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0079704456', 'name' => 'Muhammad Nur Rizqi', 'kelas' => 'XII RPL 1'],
            ['nisn' => '0072952904', 'name' => 'Muhammad Rangga Nur Ridwan', 'kelas' => 'XII RPL 1'],
        ];

        foreach ($siswa as $s) {
            User::create([
                'name' => $s['name'],
                'nisn' => $s['nisn'],
                'email' => strtolower(str_replace(' ', '.', $s['name'])) . '@siswa.smkn1boyolangu.sch.id',
                'password' => Hash::make($s['nisn']), // Password = NISN
                'role' => 'pengguna',
                'kelas' => $s['kelas'],
            ]);
        }

        // =============================================
        // SEED KATEGORI SARPRAS
        // =============================================

        $kategori = [
            ['nama' => 'Perangkat TIK', 'kode' => 'TIK', 'deskripsi' => 'Komputer, laptop, proyektor, dan perangkat IT lainnya'],
            ['nama' => 'Alat Laboratorium', 'kode' => 'LAB', 'deskripsi' => 'Alat-alat untuk praktikum lab IPA, Fisika, Kimia'],
            ['nama' => 'Buku & Referensi', 'kode' => 'BUK', 'deskripsi' => 'Buku pelajaran, referensi, dan koleksi perpustakaan'],
            ['nama' => 'Furniture', 'kode' => 'FRN', 'deskripsi' => 'Meja, kursi, lemari, dan perlengkapan ruangan'],
            ['nama' => 'Alat Olahraga', 'kode' => 'OLR', 'deskripsi' => 'Bola, raket, matras, dan perlengkapan olahraga'],
            ['nama' => 'Peralatan Audio Visual', 'kode' => 'AVS', 'deskripsi' => 'Sound system, microphone, speaker, layar proyektor'],
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
            $createdSarpras = Sarpras::create($s);
            
            // Generate unit untuk setiap sarpras sesuai jumlah stok
            $this->generateUnitsForSarpras($createdSarpras);
        }
    }

    /**
     * Generate unit individual untuk sarpras
     */
    private function generateUnitsForSarpras(Sarpras $sarpras): void
    {
        $kategori = $sarpras->kategori;
        
        // Gunakan kode kategori sebagai prefix, atau fallback ke 3 huruf pertama nama
        $prefix = $kategori && $kategori->kode 
            ? strtoupper($kategori->kode) 
            : strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $kategori->nama ?? 'XXX'), 0, 3));
        
        $prefix = str_pad($prefix, 3, 'X');

        // Cari nomor urut terakhir untuk prefix ini
        $lastKode = \App\Models\SarprasUnit::where('kode_unit', 'like', $prefix . '-%')
            ->orderBy('kode_unit', 'desc')
            ->value('kode_unit');

        $startNumber = 1;
        if ($lastKode) {
            $startNumber = (int) substr($lastKode, -3) + 1;
        }

        // Generate units sesuai jumlah stok
        for ($i = 0; $i < $sarpras->jumlah_stok; $i++) {
            $kodeUnit = $prefix . '-' . str_pad($startNumber + $i, 3, '0', STR_PAD_LEFT);
            
            \App\Models\SarprasUnit::create([
                'sarpras_id' => $sarpras->id,
                'kode_unit' => $kodeUnit,
                'kondisi' => $sarpras->kondisi === 'rusak_berat' ? 'rusak_berat' : 
                           ($sarpras->kondisi === 'rusak_ringan' ? 'rusak_ringan' : 'baik'),
                'status' => 'tersedia',
            ]);
        }
    }
}
