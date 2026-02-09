# Penjelasan Aktor dan Use Case UKK-SARPRAS

Dokumen ini menjelaskan aktor (pengguna sistem) dan fungsi/use case masing-masing sesuai project aplikasi manajemen SARPRAS.

**Kode diagram:** `use-case-diagram.mmd` (Mermaid). Buka `use-case-diagram-viewer.html` untuk melihat diagram.

---

## 1. Aktor: Siswa / Pengguna

**Role di sistem:** `pengguna`

**Deskripsi:** Siswa adalah pengguna yang membutuhkan sarana prasarana untuk kegiatan belajar. Siswa mengajukan peminjaman melalui aplikasi dan mengembalikan barang secara fisik ke petugas; pencatatan pengembalian dilakukan oleh Admin/Petugas.

**Fungsi / Use Case:**

- **Lihat Daftar Sarpras** — Melihat katalog barang yang tersedia (filter kategori, pencarian).
- **Lihat Daftar Barang untuk Pinjam** — Melihat daftar barang yang bisa dipinjam (kondisi baik, stok tersedia).
- **Ajukan Peminjaman** — Mengajukan peminjaman (jumlah, tanggal pinjam & kembali, tujuan, lokasi). Validasi: stok, double booking, durasi max 7 hari.
- **Lihat Riwayat Peminjaman** — Melihat daftar peminjaman milik sendiri.
- **Lihat Detail Peminjaman** — Melihat detail satu peminjaman.
- **Cetak Surat Peminjaman** — Mencetak surat/bukti peminjaman.
- **Buat Pengaduan** — Membuat pengaduan terkait sarpras (barang yang pernah dipinjam, judul, lokasi, deskripsi).
- **Lihat Daftar Pengaduan** — Melihat daftar pengaduan milik sendiri.
- **Lihat Detail Pengaduan** — Melihat detail satu pengaduan.
- **Profile** — Melihat dan mengubah data profil.
- **Ganti Password** — Mengubah password akun.

**Batasan:** Tidak bisa menyetujui/menolak peminjaman, mengelola katalog, atau mencatat pengembalian di sistem (pengembalian dicatat oleh petugas).

---

## 2. Aktor: Guru

**Role di sistem:** `guru`

**Deskripsi:** Guru adalah pengguna yang juga meminjam sarana prasarana. Dalam project ini, Guru memiliki akses peminjaman dan pengaduan yang sama dengan Siswa; Guru dapat meminjam barang sekali pakai (consumable) jika diatur di bisnis proses.

**Fungsi / Use Case:** Sama dengan Siswa/Pengguna:

- Lihat Daftar Sarpras, Daftar Barang untuk Pinjam, Ajukan Peminjaman, Riwayat Peminjaman, Detail Peminjaman, Cetak Surat Peminjaman.
- Buat Pengaduan, Lihat Daftar Pengaduan, Lihat Detail Pengaduan.
- Profile, Ganti Password.

**Batasan:** Sama seperti Siswa untuk modul peminjaman dan pengaduan; tidak mengelola katalog atau persetujuan peminjaman.

---

## 3. Aktor: Petugas SARPRAS

**Role di sistem:** `petugas`

**Deskripsi:** Petugas mengelola katalog barang, persetujuan peminjaman, serah terima (handover), pengembalian, pengaduan, barang hilang, maintenance, dan laporan dasar. Tidak mengelola user dan activity log (khusus Admin).

**Fungsi / Use Case:**

**Umum:** Dashboard, Profile, Ganti Password.

**Katalog:** Kelola Kategori, Kelola Ruang, Lihat/Tambah/Edit/Hapus Barang SARPRAS, Kelola Unit Barang, Trash/Restore Barang.

**Peminjaman:** Daftar Peminjaman, Setujui/Tolak Peminjaman, Handover Serah Terima Barang (pilih unit + upload foto kondisi).

**Pengembalian:** Daftar Pengembalian, Scan/Input Kode Peminjaman, Catat Pengembalian (tanggal + kondisi per unit: baik/rusak/hilang).

**Barang Hilang:** Daftar Barang Hilang, Selesaikan Kasus (ditemukan atau ganti rugi).

**Pengaduan:** Lihat Daftar Pengaduan, Update Status Pengaduan, Tambah Catatan Pengaduan.

**Maintenance & Laporan:** Kelola Maintenance, Riwayat Kondisi Sarpras, Laporan Kerusakan, Laporan Asset Health.

**Batasan:** Tidak bisa mengelola user, import user, assign role, aktivasi user, atau mengakses Activity Log dan laporan Damage Analytics / Asset Lifecycle (khusus Admin).

---

## 4. Aktor: Admin

**Role di sistem:** `admin`

**Deskripsi:** Admin memiliki semua akses Petugas ditambah manajemen user (CRUD, import, role, aktivasi) dan Activity Log. Admin juga mengakses laporan lanjutan: Damage Analytics dan Asset Lifecycle.

**Fungsi / Use Case:** Semua yang bisa dilakukan Petugas, plus:

**Khusus Admin:**

- **Kelola User** — CRUD user (tambah, edit, hapus, lihat detail).
- **Import User** — Impor user dari file (misalnya Excel).
- **Assign Role** — Menetapkan role (admin, petugas, pengguna, guru) ke user.
- **Aktivasi User** — Mengaktivasi akun Pengguna/Guru agar bisa login.
- **Activity Log** — Melihat log aktivitas sistem dan mengekspor.

**Laporan tambahan:**

- **Damage Analytics** — Analisis kerusakan.
- **Asset Lifecycle** — Siklus hidup aset.

---

## Ringkasan Tabel Akses (Use Case per Aktor)

| Use Case | Siswa | Guru | Petugas | Admin |
|----------|-------|------|---------|-------|
| Daftar barang untuk pinjam, Ajukan peminjaman, Riwayat/Detail/Cetak peminjaman | ✓ | ✓ | — | — |
| Buat/Lihat pengaduan | ✓ | ✓ | — | — |
| Profile, Ganti password | ✓ | ✓ | ✓ | ✓ |
| Dashboard | — | — | ✓ | ✓ |
| Kelola Kategori, Ruang, SARPRAS, Unit, Trash | — | — | ✓ | ✓ |
| Daftar/Approve/Reject/Handover peminjaman | — | — | ✓ | ✓ |
| Scan & catat pengembalian, Barang hilang | — | — | ✓ | ✓ |
| Update status & catatan pengaduan | — | — | ✓ | ✓ |
| Maintenance, Riwayat kondisi, Laporan kerusakan & asset health | — | — | ✓ | ✓ |
| Damage Analytics, Asset Lifecycle | — | — | — | ✓ |
| Kelola User, Import, Role, Aktivasi, Activity Log | — | — | — | ✓ |

---

## Cara Melihat Diagram

1. Buka file **`use-case-diagram-viewer.html`** di browser (di folder `docs`).
2. Atau buka **https://mermaid.live/** lalu paste isi file **`use-case-diagram.mmd`**.
3. File Mermaid: **`docs/use-case-diagram.mmd`**.

Diagram dan penjelasan di atas disesuaikan dengan route dan role di project UKK-SARPRAS (Laravel).
