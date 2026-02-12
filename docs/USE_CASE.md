# USE CASE — Sistem Manajemen Sarana dan Prasarana (SARPRAS)

**Institusi:** SMK Negeri 1 Boyolangu  
**Aplikasi:** Sistem Manajemen SARPRAS  
**Tanggal:** 11 Februari 2026

---

## 1. DESKRIPSI SISTEM

Sistem Manajemen SARPRAS adalah aplikasi berbasis web (Laravel) yang dirancang untuk mengelola sarana dan prasarana sekolah secara efisien. Sistem ini mencakup pengelolaan inventaris barang, peminjaman, pengembalian, pengaduan kerusakan, maintenance, dan pelaporan.

---

## 2. IDENTIFIKASI AKTOR

| No | Aktor             | Role di Sistem | Deskripsi Singkat                                                         |
|----|--------------------|----------------|---------------------------------------------------------------------------|
| 1  | Siswa              | `pengguna`     | Pengguna yang meminjam barang untuk kegiatan belajar mengajar              |
| 2  | Guru               | `guru`         | Pendidik yang meminjam barang, termasuk barang sekali pakai (consumable)   |
| 3  | Petugas SARPRAS    | `petugas`      | Operator yang mengelola inventaris, peminjaman, pengembalian & maintenance |
| 4  | Admin Sistem       | `admin`        | Pengelola sistem dengan hak akses tertinggi                               |

---

## 3. PENJELASAN AKTOR DAN FUNGSINYA

### 3.1 Aktor 1: Siswa (Role: `pengguna`)

**Deskripsi:**
Siswa adalah pengguna utama sistem yang membutuhkan sarana prasarana untuk kegiatan belajar mengajar. Siswa mengajukan peminjaman barang melalui aplikasi dan mengembalikan barang secara fisik ke petugas. Pencatatan pengembalian dilakukan oleh Admin/Petugas.

**Persyaratan Login:**
- Akun harus terdaftar dan **diaktivasi** oleh Admin
- Login menggunakan **NISN** (10 digit) sebagai username
- Status `is_activated = true`

**Fungsi yang Dapat Dilakukan:**

| No | Fungsi | Deskripsi | Referensi Use Case |
|----|--------|-----------|--------------------|
| 1  | Login / Logout | Masuk dan keluar dari sistem | UC-01, UC-04 |
| 2  | Register & Aktivasi Akun | Mendaftar akun baru dan mengaktivasi | UC-02, UC-03 |
| 3  | Lihat & Edit Profil | Melihat dan mengubah data profil pribadi | UC-06, UC-07 |
| 4  | Ganti Password | Mengubah password akun | UC-05 |
| 5  | Lihat Daftar Barang untuk Pinjam | Melihat katalog barang yang tersedia (stok > 0) | UC-26 |
| 6  | Ajukan Peminjaman | Mengajukan permintaan peminjaman barang (jumlah, tanggal, tujuan, lokasi) | UC-27 |
| 7  | Lihat Riwayat Peminjaman | Melihat daftar peminjaman milik sendiri | UC-28 |
| 8  | Lihat Detail Peminjaman | Melihat detail satu peminjaman | UC-29 |
| 9  | Cetak Surat Peminjaman | Mencetak bukti/surat peminjaman dalam format PDF | UC-30 |
| 10 | Buat Pengaduan | Membuat laporan pengaduan kerusakan barang yang pernah dipinjam | UC-45 |
| 11 | Lihat Daftar & Detail Pengaduan | Melihat daftar dan detail pengaduan milik sendiri | UC-46, UC-47 |
| 12 | Tambah Catatan Pengaduan | Menambahkan komentar/catatan pada pengaduan | UC-48 |
| 13 | Hapus Pengaduan | Menghapus pengaduan milik sendiri | UC-50 |

**Batasan / Hal yang TIDAK Bisa Dilakukan:**
- ❌ Tidak bisa menyetujui atau menolak peminjaman
- ❌ Tidak bisa mengelola katalog barang (tambah/edit/hapus)
- ❌ Tidak bisa mencatat pengembalian di sistem (dicatat oleh Petugas/Admin)
- ❌ Tidak bisa mengakses Dashboard
- ❌ Tidak bisa melihat data peminjaman atau pengaduan milik orang lain
- ❌ Tidak bisa meminjam barang consumable/sekali pakai

---

### 3.2 Aktor 2: Guru (Role: `guru`)

**Deskripsi:**
Guru adalah pendidik yang juga meminjam sarana prasarana melalui sistem. Guru memiliki akses peminjaman dan pengaduan yang **sama dengan Siswa**, dengan tambahan kemampuan meminjam **barang sekali pakai (consumable)**.

**Persyaratan Login:**
- Akun harus terdaftar dan **diaktivasi** oleh Admin
- Login menggunakan **NIP** (18 digit) sebagai username
- Status `is_activated = true`

**Fungsi yang Dapat Dilakukan:**

| No | Fungsi | Deskripsi | Referensi Use Case |
|----|--------|-----------|--------------------|
| 1  | Login / Logout | Masuk dan keluar dari sistem | UC-01, UC-04 |
| 2  | Register & Aktivasi Akun | Mendaftar akun baru dan mengaktivasi | UC-02, UC-03 |
| 3  | Lihat & Edit Profil | Melihat dan mengubah data profil pribadi | UC-06, UC-07 |
| 4  | Ganti Password | Mengubah password akun | UC-05 |
| 5  | Lihat Daftar Barang untuk Pinjam | Melihat katalog barang yang tersedia (stok > 0) | UC-26 |
| 6  | Ajukan Peminjaman | Mengajukan permintaan peminjaman barang, **termasuk barang consumable** | UC-27 |
| 7  | Lihat Riwayat Peminjaman | Melihat daftar peminjaman milik sendiri | UC-28 |
| 8  | Lihat Detail Peminjaman | Melihat detail satu peminjaman | UC-29 |
| 9  | Cetak Surat Peminjaman | Mencetak bukti/surat peminjaman | UC-30 |
| 10 | Buat Pengaduan | Membuat laporan pengaduan kerusakan barang | UC-45 |
| 11 | Lihat Daftar & Detail Pengaduan | Melihat daftar dan detail pengaduan milik sendiri | UC-46, UC-47 |
| 12 | Tambah Catatan Pengaduan | Menambahkan komentar/catatan pada pengaduan | UC-48 |
| 13 | Hapus Pengaduan | Menghapus pengaduan milik sendiri | UC-50 |

**Perbedaan dengan Siswa:**
- ✅ Guru **dapat meminjam barang consumable/sekali pakai**, sedangkan Siswa tidak bisa

**Batasan / Hal yang TIDAK Bisa Dilakukan:**
- ❌ Tidak bisa menyetujui atau menolak peminjaman
- ❌ Tidak bisa mengelola katalog barang
- ❌ Tidak bisa mencatat pengembalian di sistem
- ❌ Tidak bisa mengakses Dashboard
- ❌ Tidak bisa mengelola user atau konfigurasi sistem

---

### 3.3 Aktor 3: Petugas SARPRAS (Role: `petugas`)

**Deskripsi:**
Petugas SARPRAS adalah operator operasional sistem yang bertanggung jawab mengelola seluruh data barang inventaris, memproses peminjaman (persetujuan, serah terima), mencatat pengembalian, menangani pengaduan, memelihara barang (maintenance), dan membuat laporan. Petugas **tidak mengelola user** dan **tidak mengakses Activity Log** (khusus Admin).

**Persyaratan:**
- Akun dibuat oleh Admin
- Memahami prosedur pengelolaan SARPRAS
- Memiliki akses ke gudang/lokasi penyimpanan barang

**Fungsi yang Dapat Dilakukan:**

| No | Fungsi | Deskripsi | Referensi Use Case |
|----|--------|-----------|--------------------|
| | **— Umum —** | | |
| 1  | Login / Logout | Masuk dan keluar dari sistem | UC-01, UC-04 |
| 2  | Lihat Dashboard | Melihat ringkasan statistik sistem | UC-08 |
| 3  | Lihat & Edit Profil | Melihat dan mengubah data profil | UC-06, UC-07 |
| 4  | Ganti Password | Mengubah password akun | UC-05 |
| | **— Katalog & Inventaris —** | | |
| 5  | Kelola Kategori (CRUD) | Membuat, melihat, mengubah, dan menghapus kategori barang | UC-18 – UC-21 |
| 6  | Kelola Ruang (CRUD) | Membuat, melihat, mengubah, dan menghapus ruang penyimpanan | UC-22 – UC-25 |
| 7  | Kelola Barang SARPRAS (CRUD) | Menambah, melihat, mengubah, dan menghapus data barang inventaris | UC-09 – UC-13 |
| 8  | Kelola Unit Barang | Menambah dan menghapus unit individual per barang (kode unik) | UC-14, UC-15 |
| 9  | Trash & Restore Barang | Mengelola barang yang sudah dihapus | UC-16, UC-17, UC-74 |
| | **— Peminjaman —** | | |
| 10 | Lihat Daftar Peminjaman | Melihat semua peminjaman dari seluruh pengguna | UC-31 |
| 11 | Setujui Peminjaman | Menyetujui permintaan peminjaman | UC-32 |
| 12 | Tolak Peminjaman | Menolak permintaan peminjaman dengan alasan | UC-33 |
| 13 | Handover / Serah Terima | Mencatat serah terima barang (pilih unit + upload foto kondisi awal) | UC-34 |
| 14 | Lihat Detail & Cetak Peminjaman | Melihat detail dan mencetak surat peminjaman | UC-29, UC-30 |
| | **— Pengembalian —** | | |
| 15 | Scan Kode Peminjaman | Scan QR code / input kode untuk memulai proses pengembalian | UC-38 |
| 16 | Catat Pengembalian | Mencatat pengembalian barang (tanggal + kondisi per unit: baik/rusak/hilang) | UC-39 |
| 17 | Lihat Daftar & Detail Pengembalian | Melihat catatan semua pengembalian | UC-37, UC-40 |
| | **— Barang Hilang —** | | |
| 18 | Lihat Daftar Barang Hilang | Melihat barang yang dilaporkan hilang saat pengembalian | UC-41 |
| 19 | Selesaikan Kasus Barang Hilang | Menyelesaikan kasus (ditemukan kembali atau ganti rugi) | UC-43, UC-44 |
| | **— Pengaduan —** | | |
| 20 | Lihat Daftar & Detail Pengaduan | Melihat semua pengaduan dari seluruh pengguna | UC-46, UC-47 |
| 21 | Update Status Pengaduan | Mengubah status pengaduan (menunggu → diproses → selesai) | UC-49 |
| 22 | Tambah Catatan Pengaduan | Menambahkan catatan tindak lanjut pada pengaduan | UC-48 |
| | **— Maintenance & Laporan —** | | |
| 23 | Kelola Maintenance (CRUD) | Membuat, melihat, mengubah, menghapus catatan maintenance | UC-52 – UC-56 |
| 24 | Riwayat Kondisi Sarpras | Melihat riwayat perubahan kondisi per barang | UC-57 |
| 25 | Laporan Kerusakan | Melihat laporan kerusakan dan tindak lanjut | UC-58 |
| 26 | Laporan Asset Health | Melihat laporan kesehatan aset keseluruhan | UC-59 |
| 27 | Laporan Peminjaman & Export | Melihat dan mengekspor laporan statistik peminjaman | UC-60, UC-61 |

**Batasan / Hal yang TIDAK Bisa Dilakukan:**
- ❌ Tidak bisa mengelola user (CRUD, import, assign role, aktivasi)
- ❌ Tidak bisa mengakses Activity Log
- ❌ Tidak bisa mengakses laporan Damage Analytics dan Asset Lifecycle (khusus Admin)
- ❌ Tidak bisa mengajukan peminjaman

---

### 3.4 Aktor 4: Admin Sistem (Role: `admin`)

**Deskripsi:**
Admin adalah pengguna dengan **hak akses tertinggi** dalam sistem. Admin memiliki **seluruh akses yang dimiliki Petugas**, ditambah kemampuan mengelola user (CRUD, import, role, aktivasi), mengakses Activity Log, dan melihat laporan analitik lanjutan (Damage Analytics & Asset Lifecycle).

**Persyaratan:**
- Akun super admin dibuat saat instalasi sistem
- Bertanggung jawab penuh atas integritas data dan keamanan sistem

**Fungsi yang Dapat Dilakukan:**

| No | Fungsi | Deskripsi | Referensi Use Case |
|----|--------|-----------|--------------------|
| | **— Semua Fungsi Petugas —** | | |
| 1–27 | *(Sama seperti Petugas)* | Seluruh fungsi Petugas SARPRAS (lihat bagian 3.3) | UC-01 – UC-61 |
| | **— Manajemen Pengguna (Khusus Admin) —** | | |
| 28 | Lihat Daftar User | Melihat seluruh pengguna sistem | UC-64 |
| 29 | Tambah User | Menambahkan user baru (nama, username, password, role, kelas/NIP) | UC-65 |
| 30 | Edit User | Mengubah data user | UC-66 |
| 31 | Hapus User | Menghapus akun user | UC-67 |
| 32 | Detail User | Melihat detail informasi satu user | UC-68 |
| 33 | Import User | Mengimpor data user secara massal dari file Excel | UC-69 |
| 34 | Assign Role | Menetapkan role (admin/petugas/pengguna/guru) ke user | UC-70 |
| 35 | Aktivasi/Deaktivasi User | Mengaktivasi atau menonaktifkan akun pengguna | UC-71 |
| | **— Activity Log (Khusus Admin) —** | | |
| 36 | Lihat Activity Log | Melihat log semua aktivitas user di sistem (audit trail) | UC-72 |
| 37 | Export Activity Log | Mengekspor data activity log ke file | UC-73 |
| | **— Laporan Lanjutan (Khusus Admin) —** | | |
| 38 | Damage Analytics | Analisis mendalam kerusakan barang | UC-62 |
| 39 | Asset Lifecycle | Melihat siklus hidup aset dari awal hingga penghapusan | UC-63 |

**Catatan Khusus Admin:**
- ⚠️ Hak akses tertinggi = **Tanggung jawab tertinggi**
- 🔐 Akses admin harus dijaga ketat
- 📝 Semua aksi admin tercatat dalam Activity Log
- 🚫 Activity Log tidak bisa dihapus (hanya dilihat dan diekspor)

---

## 4. DAFTAR USE CASE

### 4.1 Modul Autentikasi

| ID    | Use Case             | Aktor                          | Deskripsi                                                        |
|-------|----------------------|--------------------------------|------------------------------------------------------------------|
| UC-01 | Login                | Siswa, Guru, Petugas, Admin    | Masuk ke sistem menggunakan username dan password                 |
| UC-02 | Register             | Siswa, Guru                    | Mendaftarkan akun baru ke sistem                                 |
| UC-03 | Aktivasi Akun        | Siswa, Guru                    | Mengaktivasi akun yang sudah terdaftar agar bisa login            |
| UC-04 | Logout               | Siswa, Guru, Petugas, Admin    | Keluar dari sistem                                               |
| UC-05 | Ganti Password       | Siswa, Guru, Petugas, Admin    | Mengubah password akun                                           |

---

### 4.2 Modul Profil

| ID    | Use Case             | Aktor                          | Deskripsi                                                        |
|-------|----------------------|--------------------------------|------------------------------------------------------------------|
| UC-06 | Lihat Profil         | Siswa, Guru, Petugas, Admin    | Melihat informasi profil diri sendiri                            |
| UC-07 | Edit Profil          | Siswa, Guru, Petugas, Admin    | Mengubah data profil (nama, foto, dll.)                          |

---

### 4.3 Modul Dashboard

| ID    | Use Case             | Aktor                          | Deskripsi                                                        |
|-------|----------------------|--------------------------------|------------------------------------------------------------------|
| UC-08 | Lihat Dashboard      | Petugas, Admin                 | Melihat ringkasan statistik sistem (total barang, peminjaman aktif, pengaduan, dll.) |

---

### 4.4 Modul Katalog / Inventaris SARPRAS

| ID    | Use Case                     | Aktor              | Deskripsi                                                          |
|-------|-------------------------------|---------------------|--------------------------------------------------------------------|
| UC-09 | Lihat Daftar Sarpras          | Petugas, Admin      | Melihat seluruh daftar barang inventaris dengan filter dan pencarian |
| UC-10 | Tambah Barang Sarpras         | Petugas, Admin      | Menambahkan barang baru ke katalog (kode, nama, kategori, stok, foto) |
| UC-11 | Edit Barang Sarpras           | Petugas, Admin      | Mengubah informasi barang yang sudah ada                           |
| UC-12 | Hapus Barang Sarpras          | Petugas, Admin      | Menghapus barang dari katalog (soft delete)                        |
| UC-13 | Lihat Detail Barang           | Petugas, Admin      | Melihat detail lengkap satu barang beserta unit-unitnya            |
| UC-14 | Tambah Unit Barang            | Petugas, Admin      | Menambahkan unit individual pada barang (setiap unit punya kode unik) |
| UC-15 | Hapus Unit Barang             | Petugas, Admin      | Menghapus unit individual dari barang                              |
| UC-16 | Restore Barang (Trash)        | Petugas, Admin      | Mengembalikan barang yang sudah dihapus (soft delete)              |
| UC-17 | Hapus Permanen Barang         | Petugas, Admin      | Menghapus barang secara permanen dari database                     |

---

### 4.5 Modul Kategori

| ID    | Use Case                     | Aktor              | Deskripsi                                                          |
|-------|-------------------------------|---------------------|--------------------------------------------------------------------|
| UC-18 | Lihat Daftar Kategori         | Petugas, Admin      | Melihat daftar kategori barang                                     |
| UC-19 | Tambah Kategori               | Petugas, Admin      | Menambahkan kategori baru                                          |
| UC-20 | Edit Kategori                 | Petugas, Admin      | Mengubah data kategori                                             |
| UC-21 | Hapus Kategori                | Petugas, Admin      | Menghapus kategori                                                 |

---

### 4.6 Modul Ruang

| ID    | Use Case                     | Aktor              | Deskripsi                                                          |
|-------|-------------------------------|---------------------|--------------------------------------------------------------------|
| UC-22 | Lihat Daftar Ruang            | Petugas, Admin      | Melihat daftar ruang/lokasi penyimpanan                            |
| UC-23 | Tambah Ruang                  | Petugas, Admin      | Menambahkan ruang baru                                             |
| UC-24 | Edit Ruang                    | Petugas, Admin      | Mengubah data ruang                                                |
| UC-25 | Hapus Ruang                   | Petugas, Admin      | Menghapus ruang                                                    |

---

### 4.7 Modul Peminjaman

| ID    | Use Case                          | Aktor              | Deskripsi                                                          |
|-------|------------------------------------|---------------------|--------------------------------------------------------------------|
| UC-26 | Lihat Daftar Barang untuk Pinjam   | Siswa, Guru         | Melihat katalog barang yang tersedia untuk dipinjam (stok > 0)      |
| UC-27 | Ajukan Peminjaman                  | Siswa, Guru         | Mengajukan permintaan peminjaman barang (jumlah, tanggal, tujuan)  |
| UC-28 | Lihat Riwayat Peminjaman           | Siswa, Guru         | Melihat riwayat peminjaman milik sendiri                           |
| UC-29 | Lihat Detail Peminjaman            | Siswa, Guru, Petugas, Admin | Melihat detail satu peminjaman                             |
| UC-30 | Cetak Surat Peminjaman             | Siswa, Guru, Petugas, Admin | Mencetak bukti/surat peminjaman (PDF)                      |
| UC-31 | Lihat Daftar Peminjaman (Semua)    | Petugas, Admin      | Melihat semua peminjaman dari seluruh pengguna                     |
| UC-32 | Setujui Peminjaman                 | Petugas, Admin      | Menyetujui permintaan peminjaman yang masuk                        |
| UC-33 | Tolak Peminjaman                   | Petugas, Admin      | Menolak permintaan peminjaman dengan alasan                        |
| UC-34 | Handover / Serah Terima Barang     | Petugas, Admin      | Mencatat serah terima barang (pilih unit, upload foto kondisi)     |
| UC-35 | Hapus Peminjaman                   | Petugas, Admin      | Menghapus data peminjaman (soft delete)                            |
| UC-36 | Restore Peminjaman (Trash)         | Petugas, Admin      | Mengembalikan peminjaman yang sudah dihapus                        |

---

### 4.8 Modul Pengembalian

| ID    | Use Case                          | Aktor              | Deskripsi                                                          |
|-------|------------------------------------|---------------------|--------------------------------------------------------------------|
| UC-37 | Lihat Daftar Pengembalian          | Petugas, Admin      | Melihat semua catatan pengembalian                                 |
| UC-38 | Scan Kode Peminjaman               | Petugas, Admin      | Scan QR code/input kode peminjaman untuk memulai pengembalian      |
| UC-39 | Catat Pengembalian                 | Petugas, Admin      | Mencatat pengembalian barang (kondisi per unit: baik/rusak/hilang) |
| UC-40 | Lihat Detail Pengembalian          | Petugas, Admin      | Melihat detail satu catatan pengembalian                           |

---

### 4.9 Modul Barang Hilang

| ID    | Use Case                          | Aktor              | Deskripsi                                                          |
|-------|------------------------------------|---------------------|--------------------------------------------------------------------|
| UC-41 | Lihat Daftar Barang Hilang         | Petugas, Admin      | Melihat daftar barang yang dilaporkan hilang saat pengembalian     |
| UC-42 | Detail Barang Hilang               | Petugas, Admin      | Melihat detail kasus barang hilang                                 |
| UC-43 | Selesaikan Kasus Ditemukan         | Petugas, Admin      | Menyelesaikan kasus barang hilang karena ditemukan kembali         |
| UC-44 | Selesaikan Kasus Ganti Rugi        | Petugas, Admin      | Menyelesaikan kasus barang hilang dengan ganti rugi                |

---

### 4.10 Modul Pengaduan

| ID    | Use Case                          | Aktor              | Deskripsi                                                          |
|-------|------------------------------------|---------------------|--------------------------------------------------------------------|
| UC-45 | Buat Pengaduan                     | Siswa, Guru         | Membuat pengaduan kerusakan barang yang pernah dipinjam            |
| UC-46 | Lihat Daftar Pengaduan             | Siswa, Guru, Petugas, Admin | Melihat daftar pengaduan (pengguna: milik sendiri; petugas/admin: semua) |
| UC-47 | Lihat Detail Pengaduan             | Siswa, Guru, Petugas, Admin | Melihat detail satu pengaduan                              |
| UC-48 | Tambah Catatan Pengaduan           | Siswa, Guru, Petugas, Admin | Menambahkan catatan/komentar pada pengaduan                |
| UC-49 | Update Status Pengaduan            | Petugas, Admin      | Mengubah status pengaduan (menunggu → diproses → selesai)          |
| UC-50 | Hapus Pengaduan                    | Siswa, Guru, Petugas, Admin | Menghapus pengaduan (soft delete)                          |
| UC-51 | Restore Pengaduan (Trash)          | Petugas, Admin      | Mengembalikan pengaduan yang sudah dihapus                         |

---

### 4.11 Modul Maintenance

| ID    | Use Case                          | Aktor              | Deskripsi                                                          |
|-------|------------------------------------|---------------------|--------------------------------------------------------------------|
| UC-52 | Lihat Daftar Maintenance           | Petugas, Admin      | Melihat semua record maintenance                                  |
| UC-53 | Tambah Maintenance Record          | Petugas, Admin      | Membuat catatan maintenance baru (jenis, biaya, status)            |
| UC-54 | Edit Maintenance Record            | Petugas, Admin      | Mengubah data maintenance                                         |
| UC-55 | Hapus Maintenance Record           | Petugas, Admin      | Menghapus catatan maintenance                                     |
| UC-56 | Detail Maintenance                 | Petugas, Admin      | Melihat detail satu maintenance record                             |

---

### 4.12 Modul Laporan

| ID    | Use Case                          | Aktor              | Deskripsi                                                          |
|-------|------------------------------------|---------------------|--------------------------------------------------------------------|
| UC-57 | Riwayat Kondisi Sarpras            | Petugas, Admin      | Melihat riwayat perubahan kondisi per barang                       |
| UC-58 | Laporan Kerusakan                  | Petugas, Admin      | Melihat laporan kerusakan barang dan tindak lanjut                 |
| UC-59 | Laporan Asset Health               | Petugas, Admin      | Melihat laporan kesehatan aset secara keseluruhan                  |
| UC-60 | Laporan Peminjaman                 | Petugas, Admin      | Melihat laporan statistik peminjaman dengan filter                 |
| UC-61 | Export Laporan Peminjaman          | Petugas, Admin      | Mengekspor data laporan peminjaman ke file                         |
| UC-62 | Damage Analytics                   | Admin               | Analisis mendalam kerusakan barang                                 |
| UC-63 | Asset Lifecycle                    | Admin               | Melihat siklus hidup aset dari pembelian hingga penghapusan       |

---

### 4.13 Modul Manajemen Pengguna

| ID    | Use Case                          | Aktor              | Deskripsi                                                          |
|-------|------------------------------------|---------------------|--------------------------------------------------------------------|
| UC-64 | Lihat Daftar User                  | Admin               | Melihat seluruh daftar pengguna sistem                             |
| UC-65 | Tambah User                        | Admin               | Menambahkan user baru ke sistem                                    |
| UC-66 | Edit User                          | Admin               | Mengubah data user (nama, role, kelas, dll.)                       |
| UC-67 | Hapus User                         | Admin               | Menghapus akun user                                                |
| UC-68 | Detail User                        | Admin               | Melihat detail informasi satu user                                 |
| UC-69 | Import User                        | Admin               | Mengimpor data user secara massal dari file Excel                  |
| UC-70 | Assign Role                        | Admin               | Menetapkan role (admin/petugas/pengguna/guru) ke user              |
| UC-71 | Aktivasi/Deaktivasi User           | Admin               | Mengaktivasi atau menonaktifkan akun pengguna                      |

---

### 4.14 Modul Activity Log

| ID    | Use Case                          | Aktor              | Deskripsi                                                          |
|-------|------------------------------------|---------------------|--------------------------------------------------------------------|
| UC-72 | Lihat Activity Log                 | Admin               | Melihat log semua aktivitas user di sistem                         |
| UC-73 | Export Activity Log                | Admin               | Mengekspor data activity log ke file                               |

---

### 4.15 Modul Trash (Terpadu)

| ID    | Use Case                          | Aktor              | Deskripsi                                                          |
|-------|------------------------------------|---------------------|--------------------------------------------------------------------|
| UC-74 | Lihat Trash Terpadu                | Petugas, Admin      | Melihat semua data yang sudah dihapus (Sarpras, Peminjaman, Pengaduan) dalam satu halaman |

---

## 5. TABEL AKSES USE CASE PER AKTOR

| Modul               | Siswa (Pengguna) | Guru | Petugas | Admin |
|----------------------|:--:|:--:|:--:|:--:|
| Login / Logout                      | ✓ | ✓ | ✓ | ✓ |
| Register & Aktivasi                 | ✓ | ✓ | — | — |
| Profil & Ganti Password             | ✓ | ✓ | ✓ | ✓ |
| Dashboard                           | — | — | ✓ | ✓ |
| Lihat Barang untuk Pinjam           | ✓ | ✓ | — | — |
| Ajukan Peminjaman                   | ✓ | ✓ | — | — |
| Riwayat Peminjaman Sendiri          | ✓ | ✓ | — | — |
| Detail & Cetak Peminjaman           | ✓ | ✓ | ✓ | ✓ |
| Kelola Peminjaman (Approve/Reject)  | — | — | ✓ | ✓ |
| Handover Serah Terima               | — | — | ✓ | ✓ |
| Scan & Catat Pengembalian           | — | — | ✓ | ✓ |
| Kelola Barang Hilang                | — | — | ✓ | ✓ |
| Buat Pengaduan                      | ✓ | ✓ | — | — |
| Lihat Daftar & Detail Pengaduan    | ✓ | ✓ | ✓ | ✓ |
| Update Status Pengaduan             | — | — | ✓ | ✓ |
| Kelola Kategori                     | — | — | ✓ | ✓ |
| Kelola Ruang                        | — | — | ✓ | ✓ |
| Kelola SARPRAS (CRUD + Unit)        | — | — | ✓ | ✓ |
| Kelola Maintenance                  | — | — | ✓ | ✓ |
| Laporan (Kerusakan, Asset Health, Peminjaman) | — | — | ✓ | ✓ |
| Damage Analytics & Asset Lifecycle  | — | — | — | ✓ |
| Kelola User (CRUD, Import, Role)    | — | — | — | ✓ |
| Aktivasi/Deaktivasi User            | — | — | — | ✓ |
| Activity Log & Export               | — | — | — | ✓ |
| Trash Terpadu                       | — | — | ✓ | ✓ |

**Keterangan:** ✓ = Memiliki akses | — = Tidak memiliki akses

---

## 6. SKENARIO USE CASE UTAMA

### UC-27: Ajukan Peminjaman

| Aspek             | Detail                                                                 |
|-------------------|------------------------------------------------------------------------|
| **Aktor**         | Siswa / Guru                                                           |
| **Deskripsi**     | Pengguna mengajukan peminjaman barang melalui sistem                   |
| **Pre-condition** | Pengguna sudah login, akun sudah diaktivasi, barang memiliki stok > 0  |
| **Post-condition**| Data peminjaman tersimpan dengan status "Menunggu Persetujuan"         |

**Alur Utama (Main Flow):**
1. Pengguna membuka halaman **Daftar Barang untuk Pinjam**
2. Sistem menampilkan daftar barang yang tersedia (stok > 0)
3. Pengguna memilih barang yang ingin dipinjam
4. Sistem menampilkan form peminjaman
5. Pengguna mengisi data: jumlah, tanggal pinjam, tanggal kembali, tujuan penggunaan, lokasi
6. Pengguna klik **Ajukan Peminjaman**
7. Sistem memvalidasi data (stok cukup, durasi max 7 hari)
8. Sistem menyimpan peminjaman dengan status **"Menunggu Persetujuan"**
9. Sistem menampilkan pesan sukses

**Alur Alternatif:**
- **5a.** Stok tidak mencukupi → Sistem menampilkan pesan error
- **5b.** Durasi lebih dari 7 hari → Sistem menampilkan pesan error

---

### UC-32: Setujui Peminjaman

| Aspek             | Detail                                                                 |
|-------------------|------------------------------------------------------------------------|
| **Aktor**         | Petugas / Admin                                                        |
| **Deskripsi**     | Menyetujui permintaan peminjaman yang masuk                            |
| **Pre-condition** | Peminjaman berstatus "Menunggu Persetujuan"                            |
| **Post-condition**| Status berubah menjadi "Disetujui", kode peminjaman di-generate        |

**Alur Utama:**
1. Petugas/Admin membuka halaman **Daftar Peminjaman**
2. Sistem menampilkan daftar peminjaman dengan filter status
3. Petugas memilih peminjaman yang berstatus "Menunggu"
4. Petugas klik **Setujui**
5. Sistem mengubah status menjadi **"Disetujui"**
6. Sistem men-generate kode peminjaman unik (beserta QR code)

---

### UC-34: Handover / Serah Terima Barang

| Aspek             | Detail                                                                 |
|-------------------|------------------------------------------------------------------------|
| **Aktor**         | Petugas / Admin                                                        |
| **Deskripsi**     | Mencatat serah terima barang kepada peminjam                           |
| **Pre-condition** | Peminjaman berstatus "Disetujui"                                       |
| **Post-condition**| Status berubah menjadi "Dipinjam", unit barang terpilih dan terkunci   |

**Alur Utama:**
1. Petugas/Admin membuka detail peminjaman yang sudah disetujui
2. Petugas klik **Handover**
3. Sistem menampilkan form serah terima (pilih unit spesifik, upload foto kondisi)
4. Petugas memilih unit barang dan mengupload foto kondisi awal
5. Petugas klik **Simpan**
6. Sistem meng-update status menjadi **"Dipinjam"** dan mengurangi stok

---

### UC-39: Catat Pengembalian

| Aspek             | Detail                                                                 |
|-------------------|------------------------------------------------------------------------|
| **Aktor**         | Petugas / Admin                                                        |
| **Deskripsi**     | Mencatat pengembalian barang oleh peminjam                             |
| **Pre-condition** | Peminjaman berstatus "Dipinjam"                                        |
| **Post-condition**| Pengembalian tercatat, stok diperbarui, status unit diperbarui         |

**Alur Utama:**
1. Petugas/Admin membuka halaman **Scan Pengembalian**
2. Petugas scan QR code atau input kode peminjaman manual
3. Sistem menampilkan detail peminjaman dan unit-unit yang dipinjam
4. Petugas mencatat kondisi pengembalian per unit (**Baik** / **Rusak** / **Hilang**)
5. Petugas mengupload foto kondisi pengembalian (opsional, tidak untuk barang hilang)
6. Petugas klik **Simpan Pengembalian**
7. Sistem menyimpan catatan pengembalian
8. Sistem memperbarui kondisi dan stok barang

**Alur Alternatif:**
- **4a.** Jika unit dikembalikan dalam kondisi **Rusak** → Sistem mencatat kerusakan, unit ditandai rusak
- **4b.** Jika unit dikembalikan dalam kondisi **Hilang** → Sistem mencatat sebagai barang hilang, masuk ke modul Barang Hilang

---

### UC-45: Buat Pengaduan

| Aspek             | Detail                                                                 |
|-------------------|------------------------------------------------------------------------|
| **Aktor**         | Siswa / Guru                                                           |
| **Deskripsi**     | Membuat pengaduan kerusakan barang yang berkaitan dengan peminjaman     |
| **Pre-condition** | Pengguna pernah meminjam barang (riwayat peminjaman ada)               |
| **Post-condition**| Pengaduan tersimpan dengan status "Menunggu"                           |

**Alur Utama:**
1. Pengguna membuka halaman **Buat Pengaduan**
2. Sistem menampilkan daftar barang yang pernah dipinjam oleh pengguna
3. Pengguna memilih barang dari riwayat peminjaman
4. Sistem mengisi otomatis informasi peminjaman (tanggal, tujuan, dll.)
5. Pengguna mengisi judul, deskripsi, dan lokasi kejadian
6. Pengguna klik **Kirim Pengaduan**
7. Sistem menyimpan pengaduan dengan status **"Menunggu"**

---

### UC-64–UC-71: Kelola User (Admin)

| Aspek             | Detail                                                                 |
|-------------------|------------------------------------------------------------------------|
| **Aktor**         | Admin                                                                  |
| **Deskripsi**     | Mengelola seluruh data pengguna sistem                                 |
| **Pre-condition** | User memiliki role Admin                                               |
| **Post-condition**| Data user dikelola sesuai operasi yang dilakukan                       |

**Sub-use case:**
1. **Tambah User** — Admin mengisi form (nama, username, password, role, kelas/NIP)
2. **Edit User** — Admin mengubah data user yang sudah ada
3. **Hapus User** — Admin menghapus akun user
4. **Import User** — Admin mengupload file Excel untuk impor data siswa/guru secara massal
5. **Assign Role** — Admin menetapkan role ke user
6. **Aktivasi User** — Admin mengaktivasi akun agar user bisa login

---

## 7. DIAGRAM USE CASE

> Diagram Use Case tersedia dalam format:
> - **PlantUML:** [`use-case-diagram.puml`](use-case-diagram.puml)
> - **Mermaid:** [`use-case-diagram.mmd`](use-case-diagram.mmd)
> - **Viewer HTML:** [`use-case-diagram-viewer.html`](use-case-diagram-viewer.html)

**Hubungan antar use case:**

| Relasi                           | Tipe         |
|----------------------------------|--------------|
| Ajukan Peminjaman → Upload Foto  | `<<include>>` |
| Catat Pengambilan → Upload Foto  | `<<include>>` |
| Catat Pengembalian → Upload Foto | `<<include>>` |
| Verifikasi Kerusakan → Update Kondisi Barang | `<<include>>` |
| Respons Pengaduan → Buat Maintenance | `<<extend>>` |
| Manage User → Assign Role        | `<<include>>` |
| Manage User → Aktivasi User      | `<<include>>` |

---

## 8. ALUR KERJA UTAMA (WORKFLOW)

### Workflow Peminjaman Barang

```
Siswa/Guru                   Petugas/Admin
    │                              │
    ├── Ajukan Peminjaman ────────►│
    │   (Status: Menunggu)         │
    │                              ├── Review Peminjaman
    │                              │
    │                   ┌──────────┤
    │                   │          │
    │              Disetujui    Ditolak
    │                   │       (Selesai)
    │                   │
    │                   ├── Handover Serah Terima
    │                   │   (Status: Dipinjam)
    │                   │
    │  ◄────────────────┤
    │  Terima Barang    │
    │                   │
    ├── Gunakan Barang  │
    │                   │
    ├── Kembalikan ────►│
    │                   ├── Catat Pengembalian
    │                   │   (Kondisi: Baik/Rusak/Hilang)
    │                   │
    │                   ├── Jika Rusak → Maintenance
    │                   ├── Jika Hilang → Barang Hilang
    │                   │   (Status: Dikembalikan)
    │                   │
```

### Workflow Pengaduan

```
Siswa/Guru                   Petugas/Admin
    │                              │
    ├── Buat Pengaduan ──────────►│
    │   (Status: Menunggu)         │
    │                              ├── Review Pengaduan
    │                              ├── Update Status (Diproses)
    │                              ├── Tambah Catatan
    │                              ├── Tindak Lanjut
    │                              ├── Update Status (Selesai)
    │                              │
```

---

## 9. CATATAN TAMBAHAN

1. **Siswa** login menggunakan **NISN** (10 digit) sebagai username
2. **Guru** login menggunakan **NIP** (18 digit) sebagai username
3. **Barang consumable/sekali pakai** hanya dapat dipinjam oleh **Guru**
4. Semua aktivitas penting dicatat dalam **Activity Log** untuk audit trail
5. Sistem mendukung **QR Code** untuk identifikasi peminjaman saat pengembalian
6. **Trash terpadu** menggabungkan data terhapus dari Sarpras, Peminjaman, dan Pengaduan

---

**Dokumen ini dibuat untuk keperluan Ujian Kompetensi Keahlian (UKK)**  
**SMK Negeri 1 Boyolangu — Tahun 2026**
