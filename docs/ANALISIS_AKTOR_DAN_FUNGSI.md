# ANALISIS AKTOR DAN FUNGSI SISTEM MANAJEMEN SARPRAS

**Sistem Manajemen Sarana dan Prasarana Sekolah (SARPRAS)**  
**Tanggal:** 8 Februari 2026

---

## DAFTAR ISI

1. [Pendahuluan](#pendahuluan)
2. [Daftar Aktor](#daftar-aktor)
3. [Penjelasan Aktor dan Fungsi](#penjelasan-aktor-dan-fungsi)
4. [Tabel Akses Kontrol](#tabel-akses-kontrol)
5. [Workflow Utama](#workflow-utama)

---

## PENDAHULUAN

Sistem Manajemen SARPRAS adalah aplikasi berbasis web yang dirancang untuk mengelola sarana dan prasarana sekolah secara efisien. Sistem ini melibatkan 4 (empat) aktor utama dengan peran dan tanggung jawab yang berbeda-beda sesuai dengan kebutuhan operasional sekolah.

---

## DAFTAR AKTOR

| No | Aktor | Role | Status |
|----|-------|------|--------|
| 1 | Siswa | `siswa` | Pengguna Sistem |
| 2 | Guru | `guru` | Pengguna Sistem |
| 3 | Petugas SARPRAS | `petugas` | Pengguna Sistem |
| 4 | Admin Sistem | `admin` | Pengelola Sistem |

---

## PENJELASAN AKTOR DAN FUNGSI

### 1. SISWA (Role: `siswa`)

#### Deskripsi:
Siswa adalah pengguna utama yang membutuhkan sarana prasarana untuk kegiatan belajar mengajar. Siswa dapat mengajukan permintaan peminjaman barang yang dibutuhkan untuk keperluan akademik.

#### Persyaratan:
- Akun harus terdaftar dan diaktifkan oleh admin
- Status akun `is_activated = true`
- Memiliki NISN (Nomor Induk Siswa Nasional) yang valid
- Memiliki informasi kelas yang jelas

#### Fungsi dan Hak Akses:

| No | Fungsi | Deskripsi | Batasan |
|----|--------|-----------|---------|
| 1 | Melihat Katalog Barang | Melihat daftar semua sarana prasarana yang tersedia beserta stok dan kondisinya | Hanya barang yang tidak dalam kondisi `rusak_berat` |
| 2 | Ajukan Peminjaman | Mengajukan permintaan peminjaman barang dengan menentukan jumlah, tanggal, dan tujuan penggunaan | Maksimal jumlah sesuai stok yang tersedia |
| 3 | Upload Foto Kondisi | Mengupload foto kondisi barang pada saat mengajukan peminjaman (opsional) | Format JPG/PNG, max 2MB |
| 4 | Lihat Status Peminjaman | Melihat status permintaan peminjaman: menunggu, disetujui, ditolak, dipinjam, atau dikembalikan | Hanya peminjaman milik sendiri |
| 5 | Lapor Kerusakan | Melaporkan kerusakan atau masalah pada barang yang sedang dipinjam atau dilihat | Harus memberikan deskripsi jelas dan foto bukti |
| 6 | Lihat Riwayat Peminjaman | Melihat history peminjaman sebelumnya dengan detail waktu, barang, dan status | Data sesuai dengan profile sendiri |
| 7 | Kembalikan Barang | Mengembalikan barang pinjaman dan melaporkan kondisi saat pengembalian | Hanya barang milik sendiri yang status `dipinjam` |
| 8 | Terima Notifikasi | Menerima notifikasi persetujuan/penolakan peminjaman dan reminder pengembalian | Otomatis berdasarkan action yang dilakukan |

#### Contoh Use Case:
```
1. Siswa login ke sistem
2. Lihat katalog barang yang tersedia
3. Cari proyektor untuk keperluan presentasi
4. Ajukan peminjaman untuk hari esok hari
5. Tunggu persetujuan dari petugas/guru
6. Jika disetujui, ambil barang dan lakukan confirm
7. Gunakan barang sesuai tujuan
8. Kembalikan barang dengan kondisi baik
9. Sistem update status menjadi "dikembalikan"
```

#### Batasan dan Kontrol:
- ❌ Tidak bisa menyetujui peminjaman
- ❌ Tidak bisa mengubah data katalog barang
- ❌ Tidak bisa menghapus data
- ❌ Tidak bisa melihat data peminjaman orang lain
- ⚠️ Akan mendapat **peringatan** jika:
  - Terlambat mengembalikan barang
  - Mengembalikan barang dalam kondisi rusak tanpa dilaporkan
  - Melaporkan kerusakan secara berulang tanpa sebab jelas

---

### 2. GURU (Role: `guru`)

#### Deskripsi:
Guru adalah pendidik yang berperan sebagai pengguna utama sekaligus verifikator dalam sistem. Guru dapat meminjam barang dalam jumlah lebih besar untuk keperluan pembelajaran dan dapat menyetujui/menolak permintaan peminjaman dari siswa.

#### Persyaratan:
- Akun terdaftar dan diaktifkan oleh admin
- Memiliki credential yang valid
- Memiliki role `guru` di sistem

#### Fungsi dan Hak Akses:

| No | Fungsi | Deskripsi | Batasan |
|----|--------|-----------|---------|
| 1 | **Semua Fungsi Siswa** | Guru mendapatkan semua hak akses yang dimiliki siswa | Terterapkan penuh |
| 2 | Ajukan Peminjaman dalam Jumlah Besar | Mengajukan peminjaman dengan jumlah yang lebih fleksibel untuk keperluan pembelajaran | Dapat lebih dari stok tunggal unit |
| 3 | Persetujui Peminjaman Siswa | Menerima dan mereview permintaan peminjaman dari siswa, bisa disetujui atau ditolak | Hanya permintaan yang tertunda |
| 4 | Berikan Catatan Persetujuan | Menambahkan catatan saat menyetujui atau menolak peminjaman, misal untuk memberi saran | Max 500 karakter |
| 5 | Lihat Laporan Kerusakan Siswa | Melihat semua laporan kerusakan yang dibuat siswa | Detail lengkap dengan foto dan deskripsi |
| 6 | Lihat Riwayat Peminjaman Keseluruhan | Melihat riwayat peminjaman dari semua siswa dan guru | Dengan filter tanggal dan barang |
| 7 | Monitor Status Barang | Melihat status real-time dari semua barang katalog | Kondisi, stok, dan unit yang sedang dipinjam |
| 8 | Respons Laporan Kerusakan | Memberikan tanggapan awal pada laporan kerusakan (opsional) | Sebelum ditangani petugas |

#### Contoh Use Case:
```
1. Guru login ke sistem
2. Lihat permintaan peminjaman siswa yang menunggu persetujuan
3. Review detail:
   - Barang apa yang diminta?
   - Untuk tujuan apa?
   - Berapa lama dipinjam?
4. Setujui dengan memberi catatan, misal: "Baik, gunakan dengan hati-hati"
5. Siswa dapat mengambil barang
6. Jika ada laporan kerusakan, guru bisa lihat detail
7. Guru kembalikan barang miliknya sendiri
```

#### Batasan dan Kontrol:
- ❌ Tidak bisa mengubah data katalog
- ❌ Tidak bisa menghapus data
- ❌ Tidak bisa mengubah role/hak akses user lain
- ❌ Persetujuan guru tidak bersifat final (bisa ditolak oleh admin)
- ⚠️ Pertanggungjawaban untuk peminjaman yang disahkan

---

### 3. PETUGAS SARPRAS (Role: `petugas`)

#### Deskripsi:
Petugas SARPRAS adalah operator operasional sistem yang bertanggung jawab mengelola data barang, menyetujui peminjaman, mencatat pengembalian, dan melakukan pemeliharaan sarana prasarana.

#### Persyaratan:
- Akun terdaftar dan diaktifkan oleh admin
- Memahami prosedur pengelolaan SARPRAS
- Memiliki akses ke gudang/lokasi penyimpanan barang

#### Fungsi dan Hak Akses:

| No | Fungsi | Deskripsi | Batasan |
|----|--------|-----------|---------|
| 1 | **Manajemen Data Barang** | Menambah, mengubah, dan menghapus data katalog SARPRAS | Penuh kontrol atas katalog |
| 2 | Input Data Barang Baru | Membuat entry barang baru dengan kode, nama, kategori, stok, kondisi, dan foto | Kode harus unik dan terformat |
| 3 | Edit Data Barang | Mengubah informasi barang (nama, deskripsi, stok, foto) | Tidak bisa mengubah kode |
| 4 | Hapus/Archive Barang | Menghapus barang dari katalog (soft delete) | Barang tidak bisa dihapus jika masih dipinjam |
| 5 | Kelola Unit Individual Barang | Menambah dan menghapus unit individual dari setiap barang | Setiap unit punya kode unik |
| 6 | Lihat Permintaan Peminjaman | Melihat semua permintaan peminjaman yang masuk | Dengan filter status dan user |
| 7 | Persetujui/Tolak Peminjaman | Menyetujui atau menolak permintaan peminjaman dari siswa/guru | Berdasarkan ketersediaan stok |
| 8 | Catat Pengambilan Barang | Mencatat saat siswa/guru mengambil barang pinjaman | Update status menjadi "dipinjam" |
| 9 | Catat Pengembalian Barang | Mencatat barang yang dikembalikan beserta kondisi saat pengembalian | Inspect kondisi, foto, dan update stok |
| 10 | Buat Maintenance Record | Membuat catatan perawatan/perbaikan barang yang rusak atau perlu maintenance | Termasuk tanggal, jenis, biaya, status |
| 11 | Update Status Maintenance | Mengubah status maintenance dari "belum dikerjakan" → "dalam proses" → "selesai" | Track progress perbaikan |
| 12 | Respons Pengaduan Kerusakan | Merespons laporan kerusakan dengan memberikan catatan tindak lanjut | Bisa melakukan perbaikan atau eskalasi |
| 13 | Update Kondisi Barang | Mengubah status kondisi barang (baik, rusak ringan, rusak berat, perlu maintenance) | Berdasarkan inspeksi fisik |
| 14 | Lihat Laporan Statistik | Melihat laporan peminjaman, pengembalian, dan maintenance | Untuk keperluan inventaris |
| 15 | Manage Kategori SARPRAS | Menambah dan mengedit kategori barang (dengan petugas senior atau admin) | Dengan approval |

#### Contoh Use Case:
```
WORKFLOW PEMINJAMAN:
1. Petugas lihat permintaan peminjaman dari siswa
2. Check stok barang yang tersedia
3. Jika stok cukup → Setujui permintaan
4. Siswa datang untuk mengambil barang
5. Petugas serah barang dan catat pengambilan (foto kondisi)
6. Status berubah menjadi "dipinjam"
7. Siswa kembali dengan barang
8. Petugas inspect kondisi barang:
   - Jika baik → Catat pengembalian, status "dikembalikan"
   - Jika rusak → Catat damage, buat maintenance record
9. Update stok dan status barang

WORKFLOW MAINTENANCE:
1. Barang ditemukan rusak saat inspeksi
2. Petugas buat maintenance record
3. Teknisi / petugas lakukan perbaikan
4. Update status maintenance menjadi "dalam proses"
5. Setelah selesai → Update status menjadi "selesai"
6. Update kondisi barang menjadi "baik" atau sesuai hasil perbaikan
```

#### Batasan dan Kontrol:
- ❌ Tidak bisa mengubah role/permission user
- ❌ Tidak bisa menghapus data history/log
- ⚠️ Setiap aksi dicatat dalam activity log
- ✅ Pertanggungjawaban penuh atas data yang dikelola

---

### 4. ADMIN SISTEM (Role: `admin`)

#### Deskripsi:
Admin adalah pengguna dengan privilege tertinggi yang mengelola seluruh sistem, termasuk user management, konfigurasi, dan supervision terhadap aktor lain.

#### Persyaratan:
- Akun super admin yang dibuat saat instalasi sistem
- Memahami prosedur keamanan dan compliance
- Bertanggung jawab atas integritas data sistem

#### Fungsi dan Hak Akses:

| No | Fungsi | Deskripsi | Batasan |
|----|--------|-----------|---------|
| 1 | **Semua Fungsi Petugas** | Admin memiliki semua hak akses petugas SARPRAS | Penuh kontrol |
| 2 | User Management (CRUD) | Tambah, lihat, edit, dan hapus akun user | Kontrol penuh atas semua user |
| 3 | Reset Password User | Mereset password user yang lupa atau terkunci | Dengan notifikasi ke user |
| 4 | Aktivasi/Deaktivasi Akun | Mengaktifkan akun user baru (siswa, guru) | Kontrol akses sistem |
| 5 | Assign Role kepada User | Menentukan role (siswa, guru, petugas, admin) untuk setiap user | Flexible role management |
| 6 | Manage Kategori SARPRAS | Menambah, mengedit kategori barang beserta maintenance period | Definisi struktur barang |
| 7 | Manage Ruang Penyimpanan | Mengelola lokasi/ruang penyimpanan barang | Setup warehouse locations |
| 8 | Lihat Activity Log Penuh | Melihat log semua aktivitas user di sistem (create, update, delete, approve, reject) | Audit trail lengkap |
| 9 | Lihat Laporan Statistik Lengkap | Melihat dashboard dengan statistik peminjaman, kondisi barang, maintenance | Business intelligence |
| 10 | Berikan Peringatan ke User | Memberikan warning/peringatan kepada user yang melanggar aturan | Track dalam `jumlah_peringatan` |
| 11 | Suspend User | Membatalkan sementara akses user tanpa menghapus data | Untuk pengguna bermasalah |
| 12 | Backup & Export Data | Export data ke format lain (Excel, PDF, CSV) | Untuk backup dan laporan |
| 13 | Konfigurasi Sistem | Mengatur parameter sistem seperti maintenance period, penalty rules, dll | System configuration |
| 14 | Monitoring Dashboard | Melihat dashboard real-time status sistem, active users, pending approvals | System health check |

#### Contoh Use Case:
```
SETUP AWAL SISTEM:
1. Admin buat kategori barang (Elektronik, Furniture, Alat Olahraga, dll)
2. Admin setup ruang penyimpanan (Lab RPL, Perpustakaan, Gudang, dll)
3. Admin import data barang
4. Admin buat akun petugas dan guru
5. Admin aktivasi akun siswa yang sudah terdaftar
6. Sistem siap digunakan

MONITORING & MAINTENANCE:
1. Admin lihat dashboard sistem
2. Monitor permintaan peminjaman yang menunggu
3. Cek report barang yang perlu maintenance
4. Review activity log untuk audit
5. Lihat statistik penggunaan
6. Evaluate performa sistem

INCIDENT MANAGEMENT:
1. Jika ada siswa bermasalah (terlambat kembali):
   - Admin berikan peringatan (jumlah_peringatan++)
   - Track peringatan dalam sistem
   
2. Jika ada data yang salah:
   - Admin edit data langsung
   - Activity log otomatis tercatat
   
3. Jika ada user yang perlu dicegah akses:
   - Admin suspend akun sementara
   - Data tetap tersimpan
```

#### Batasan dan Kontrol:
- ⚠️ Power tertinggi = Tanggung jawab tertinggi
- 🔐 Akses admin harus dijaga ketat
- 📝 Semua action admin dicatat dalam log
- 🚫 Tidak bisa menghapus activity log
- ✅ Pertanggungjawaban penuh atas sistem

---

## TABEL AKSES KONTROL

### Comparison Matrix

| Fungsi | Siswa | Guru | Petugas | Admin |
|--------|:-----:|:----:|:-------:|:-----:|
| **Katalog & Inventory** | | | | |
| Lihat katalog barang | ✓ | ✓ | ✓ | ✓ |
| Tambah barang | ✗ | ✗ | ✓ | ✓ |
| Edit barang | ✗ | ✗ | ✓ | ✓ |
| Hapus barang | ✗ | ✗ | ✓ | ✓ |
| Kelola unit barang | ✗ | ✗ | ✓ | ✓ |
| **Peminjaman** | | | | |
| Ajukan peminjaman | ✓ | ✓ | ✗ | ✗ |
| Lihat status peminjaman sendiri | ✓ | ✓ | ✗ | ✗ |
| Lihat semua peminjaman | ✗ | ✓ | ✓ | ✓ |
| Setujui peminjaman (guru) | ✗ | ✓ | ✓ | ✓ |
| Setujui peminjaman (final) | ✗ | ✗ | ✓ | ✓ |
| Tolak peminjaman | ✗ | ✓ | ✓ | ✓ |
| Catat pengambilan | ✗ | ✗ | ✓ | ✓ |
| Catat pengembalian | ✗ | ✗ | ✓ | ✓ |
| **Kerusakan & Laporan** | | | | |
| Lapor kerusakan | ✓ | ✓ | ✓ | ✓ |
| Lihat laporan kerusakan sendiri | ✓ | ✓ | ✓ | ✓ |
| Lihat semua laporan | ✗ | ✓ | ✓ | ✓ |
| Respons laporan kerusakan | ✗ | ✗ | ✓ | ✓ |
| **Maintenance** | | | | |
| Buat maintenance record | ✗ | ✗ | ✓ | ✓ |
| Update status maintenance | ✗ | ✗ | ✓ | ✓ |
| Lihat maintenance log | ✗ | ✗ | ✓ | ✓ |
| **User Management** | | | | |
| Tambah user | ✗ | ✗ | ✗ | ✓ |
| Edit user | ✗ | ✗ | ✗ | ✓ |
| Hapus user | ✗ | ✗ | ✗ | ✓ |
| Aktivasi/deaktivasi user | ✗ | ✗ | ✗ | ✓ |
| Assign role | ✗ | ✗ | ✗ | ✓ |
| Reset password | ✗ | ✗ | ✗ | ✓ |
| **Konfigurasi & Admin** | | | | |
| Lihat activity log | ✗ | ✗ | ✗ | ✓ |
| Manage kategori | ✗ | ✗ | ✓ | ✓ |
| Manage ruang | ✗ | ✗ | ✓ | ✓ |
| Lihat dashboard | ✗ | ✓ | ✓ | ✓ |
| Export data | ✗ | ✗ | ✗ | ✓ |
| Backup sistem | ✗ | ✗ | ✗ | ✓ |

**Keterangan:**
- ✓ = Dapat mengakses
- ✗ = Tidak dapat mengakses

---

## WORKFLOW UTAMA

### Workflow 1: PEMINJAMAN BARANG

```
┌─────────┐
│  SISWA  │  1. Ajukan permintaan peminjaman
└────┬────┘
     │
     ↓
┌──────────────────────────┐
│ Status: MENUNGGU         │  2. Guru/Petugas review
│ (Pending Approval)       │
└────┬─────────────────────┘
     │
     ├─────────────────────────────┐
     │                             │
     ↓                             ↓
┌──────────────┐          ┌──────────────┐
│ DISETUJUI    │          │ DITOLAK      │
│ ✓ Persetujuan│          │ ✗ Penolakan  │
└────┬─────────┘          └──────────────┘
     │                         (Selesai)
     ↓
┌──────────────────────────┐
│ Status: DIPINJAM         │  3. Siswa ambil barang
│ (Sedang Dipinjam)        │     Petugas catat pengambilan
└────┬─────────────────────┘
     │
     ├─────────────────────────────────────┐
     │                                     │
     ↓                                     ↓
┌─────────────────────┐         ┌──────────────────┐
│ Kembalikan Tepat    │         │ Kembalikan Rusak │
│ Waktu (BAIK)        │         │ (RUSAK)          │
└────┬────────────────┘         └────┬─────────────┘
     │                               │
     ↓                               ↓
┌──────────────────────────┐  ┌──────────────────────┐
│ Status: DIKEMBALIKAN     │  │ Buat Laporan Rusak   │
│ Petugas catat kondisi    │  │ + Maintenance Record │
│ Update stok              │  │ + Perbaikan oleh     │
└──────────────────────────┘  │   teknisi/petugas    │
                              └──────────────────────┘
```

### Workflow 2: LAPORAN KERUSAKAN

```
┌─────────┐
│ SISWA   │  1. Lapor kerusakan barang
│  GURU   │     (via pengaduan)
└────┬────┘
     │
     ↓
┌──────────────────────────┐
│ Status: MENUNGGU         │  2. Petugas menerima laporan
│ (Pending Review)         │
└────┬─────────────────────┘
     │
     ↓
┌──────────────────────────┐
│ Petugas inspect barang   │  3. Petugas verifikasi kondisi
│ Tentukan penyebab        │
└────┬─────────────────────┘
     │
     ├──────────────────────────────┐
     │                              │
     ↓                              ↓
┌──────────────┐           ┌────────────────┐
│ LAYAK PAKAI  │           │ PERLU PERBAIKAN│
│ Cleanup saja │           │ Maintenance req│
└──────────────┘           └────┬───────────┘
                                │
                                ↓
                        ┌──────────────────────┐
                        │ Buat Maintenance     │
                        │ - Jenis perbaikan    │
                        │ - Estimasi biaya     │
                        │ - Responsibility     │
                        └────┬─────────────────┘
                             │
                             ↓
                        ┌──────────────────────┐
                        │ Status: DALAM PROSES │
                        │ (Sedang diperbaiki)  │
                        └────┬─────────────────┘
                             │
                             ↓
                        ┌──────────────────────┐
                        │ Perbaikan selesai    │
                        │ Update status menjadi│
                        │ "SELESAI"            │
                        │ Update kondisi barang│
                        └──────────────────────┘
                        
┌──────────────────────────┐
│ Status: SELESAI          │  4. Admin close pengaduan
│ Pengaduan ditutup        │
└──────────────────────────┘
```

### Workflow 3: MAINTENANCE BERKALA

```
┌────────────────────────────────────┐
│ Periode maintenance sudah tercapai  │
│ (maintenance_period di kategori)    │
└────┬─────────────────────────────────┘
     │
     ↓
┌──────────────────────────┐
│ Sistem flag: butuh       │  1. Sistem auto-check
│ maintenance (auto)       │
└────┬─────────────────────┘
     │
     ↓
┌──────────────────────────┐
│ Notif ke Petugas: Barang │  2. Petugas menerima notifikasi
│ butuh maintenance         │
└────┬─────────────────────┘
     │
     ↓
┌──────────────────────────┐
│ Petugas buat Maintenance │  3. Buat schedule perbaikan
│ Record                   │
└────┬─────────────────────┘
     │
     ├───────────────────────┐
     │                       │
     ↓                       ↓
┌──────────────┐      ┌───────────────┐
│ Pencegahan   │      │ Perbaikkan    │
│ (preventive) │      │ (maintenance) │
│ Cleaning     │      │ Fix issues    │
│ Calibration  │      │ Repair parts  │
└──────────────┘      └───────────────┘
                            │
                            ↓
                    ┌──────────────────┐
                    │ Status: SELESAI  │
                    │ Update kondisi   │
                    │ Reset maintenance│
                    │ counter (next    │
                    │ schedule)        │
                    └──────────────────┘
```

---

## RINGKASAN TANGGUNG JAWAB

### Siswa
- Menggunakan barang sesuai tujuan
- Merawat barang dengan baik
- Mengembalikan tepat waktu
- Melaporkan kerusakan jika terjadi

### Guru  
- Mendampingi siswa dalam penggunaan barang
- Meverifikasi peminjaman siswa
- Membantu monitoring keadaan barang
- Bertindak sebagai user premium dalam sistem

### Petugas SARPRAS
- Mengelola inventory barang
- Memproses peminjaman dan pengembalian
- Melakukan inspeksi dan maintenance
- Mencatat dan melaporkan kondisi barang

### Admin
- Mengatur user dan kontrol akses
- Memonitor operasional sistem
- Memastikan compliance dan security
- Mengelola konfigurasi sistem
- Membuat keputusan administratif (suspend, warning, dll)

---

## KESIMPULAN

Sistem Manajemen SARPRAS dirancang dengan struktur aktor yang jelas dan terpisah berdasarkan tanggung jawab masing-masing. Setiap aktor memiliki:

1. **Peran yang terdefinisi** - Sesuai dengan fungsi dan kebutuhan operasional
2. **Hak akses yang terbatas** - Principle of least privilege untuk security
3. **Kontrol dan monitoring** - Activity log untuk audit trail
4. **Workflow yang jelas** - Proses bisnis yang terstruktur

Dengan desain ini, sistem dapat beroperasi secara efisien dengan kontrol yang baik terhadap data dan proses bisnis sekolah.

---

**Dokumen ini dibuat untuk keperluan Ujian Kompetensi Keahlian (UKK)**  
**Tanggal: 8 Februari 2026**
