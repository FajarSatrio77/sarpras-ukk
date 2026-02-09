# Dokumentasi UKK-SARPRAS

Dokumentasi sistem manajemen Sarana dan Prasarana (SARPRAS).

---

## Diagram & Flowchart

| Dokumen | Deskripsi | File |
|--------|------------|------|
| **Flowchart Alur Kerja Web** | Alur masuk aplikasi, auth, peminjaman, pengembalian, pengaduan, dan menu admin | [flowchart-alur-kerja-viewer.html](flowchart-alur-kerja-viewer.html) • [flowchart-alur-kerja.mmd](flowchart-alur-kerja.mmd) |
| **Use Case Diagram** | Aktor dan use case (Siswa, Guru, Petugas, Admin) | [use-case-diagram-viewer.html](use-case-diagram-viewer.html) • [use-case-diagram.mmd](use-case-diagram.mmd) • [use-case-diagram.puml](use-case-diagram.puml) |
| **Penjelasan Aktor & Use Case** | Deskripsi aktor dan fungsi/use case sesuai project | [PENJELASAN_AKTOR_DAN_USE_CASE.md](PENJELASAN_AKTOR_DAN_USE_CASE.md) |

**Cara melihat diagram:** Buka file `*-viewer.html` di browser (double-click atau dari menu File → Open).

---

## Penjelasan Alur Proses

| File | Isi |
|------|-----|
| [PENJELASAN_ALUR_PROSES.md](PENJELASAN_ALUR_PROSES.md) | **Penjelasan singkat alur proses** (masuk, auth, peminjaman, pengembalian, pengaduan, admin) |
| [KETERANGAN_FUNGSI_HALAMAN.md](KETERANGAN_FUNGSI_HALAMAN.md) | **Keterangan fungsi tiap halaman** (ringkas per halaman) |
| [LANGKAH_PENGGUNAAN_WEBSITE.md](LANGKAH_PENGGUNAAN_WEBSITE.md) | **Langkah penggunaan website** step-by-step per role (singkat) |

---

## Dokumen Analisis

| File | Isi |
|------|-----|
| [ANALISIS_AKTOR_DAN_FUNGSI.md](ANALISIS_AKTOR_DAN_FUNGSI.md) | Analisis aktor dan fungsi sistem |
| [PANDUAN_USE_CASE_DIAGRAM.md](PANDUAN_USE_CASE_DIAGRAM.md) | Panduan use case diagram |

---

## Ringkasan Flowchart Alur Kerja

1. **Masuk aplikasi** → Login/Register → Aktivasi (jika Pengguna/Guru) → Redirect sesuai role.
2. **Peminjaman** → Daftar sarpras → Ajukan (menunggu) → Approve/Reject → Handover → Dipinjam → Pengembalian.
3. **Pengembalian** → Scan kode → Form kondisi (baik/rusak/hilang) → Simpan → Barang hilang (jika ada).
4. **Pengaduan** → Buat pengaduan (menunggu) → Admin/Petugas update status (diproses/selesai/ditutup).
5. **Admin & Petugas** → Kategori, Ruang, SARPRAS, User, Laporan, Maintenance, Barang Hilang, Activity Log.
