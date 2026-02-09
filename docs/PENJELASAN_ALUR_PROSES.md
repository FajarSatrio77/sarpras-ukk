# Penjelasan Singkat Alur Proses UKK-SARPRAS

Dokumen ini menjelaskan alur kerja utama aplikasi manajemen Sarana dan Prasarana (SARPRAS) secara singkat.

---

## 1. Masuk Aplikasi & Autentikasi

- **Pengunjung** membuka aplikasi. Jika **belum login** → diarahkan ke **Login** atau **Register**.
- **Login:** isi NISN/NIP dan password. Jika salah → kembali ke form login.
- **Pengguna/Guru** yang baru daftar harus **aktivasi** dulu (kode dari admin). Jika belum aktivasi → tidak bisa login, harus aktivasi dulu.
- Setelah login, **redirect** berdasarkan role:
  - **Pengguna / Guru** → halaman **Daftar Sarpras** (untuk pinjam).
  - **Admin / Petugas** → **Dashboard**.

---

## 2. Alur Peminjaman

| Langkah | Pelaku | Keterangan |
|--------|--------|------------|
| 1 | Pengguna/Guru | Lihat **Daftar Sarpras** yang tersedia, pilih barang. |
| 2 | Pengguna/Guru | Isi form: jumlah, tanggal pinjam & kembali, tujuan, lokasi pemakaian. Sistem validasi: stok, double booking, durasi max 7 hari (siswa). |
| 3 | Sistem | Simpan pengajuan dengan **status: menunggu**. |
| 4 | Admin/Petugas | Lihat daftar peminjaman, lalu **Approve** atau **Reject**. Jika ditolak → status **ditolak** + alasan. |
| 5 | Admin/Petugas | Jika disetujui → status **disetujui**. Lakukan **Handover**: pilih unit yang diserahkan + upload foto kondisi barang. |
| 6 | Sistem | Simpan unit yang dipinjam, kurangi stok, status peminjaman → **dipinjam**. |
| 7 | Pengguna | Membawa barang sesuai jadwal. |

Setelah itu alur dilanjutkan ke **pengembalian**.

---

## 3. Alur Pengembalian

| Langkah | Pelaku | Keterangan |
|--------|--------|------------|
| 1 | Admin/Petugas | **Scan/input kode peminjaman**. Sistem cek: kode valid dan status **dipinjam**. |
| 2 | Admin/Petugas | Isi **form pengembalian**: tanggal kembali, **kondisi per unit** (baik / rusak ringan / rusak berat / hilang). |
| 3 | Sistem | Simpan data pengembalian, update status peminjaman → **dikembalikan**, update stok dan kondisi unit. |
| 4 | Jika ada unit hilang | Masuk alur **Barang Hilang**: diselesaikan dengan **Ditemukan** atau **Ganti Rugi**. |

---

## 4. Alur Pengaduan

- **Pengguna/Guru** membuat pengaduan (barang yang pernah dipinjam, judul, lokasi, deskripsi). Status awal: **menunggu**.
- **Admin/Petugas** mengelola pengaduan: mengubah status menjadi **diproses** (bisa tambah catatan), **selesai**, atau **ditutup**.

---

## 5. Fungsi Admin & Petugas

- **Kategori & Ruang:** CRUD kategori SARPRAS dan ruang.
- **SARPRAS:** CRUD barang + unit, trash/restore.
- **User (khusus Admin):** CRUD user, import, assign role, aktivasi akun.
- **Laporan:** Kerusakan, Asset Health, Damage Analytics, Asset Lifecycle.
- **Maintenance:** CRUD catatan maintenance.
- **Barang Hilang:** Penyelesaian kasus hilang (ditemukan / ganti rugi).
- **Activity Log:** Lihat dan export log aktivitas.

---

## Ringkasan Status Peminjaman

| Status | Arti |
|--------|------|
| **menunggu** | Pengajuan baru, belum diproses admin/petugas. |
| **disetujui** | Sudah disetujui, menunggu handover (serah terima barang). |
| **dipinjam** | Barang sudah diserahkan, sedang dipinjam. |
| **dikembalikan** | Barang sudah dikembalikan dan dicatat. |
| **ditolak** | Pengajuan ditolak (ada alasan). |

---

*Diagram lengkap: [flowchart-alur-kerja-viewer.html](flowchart-alur-kerja-viewer.html)*
