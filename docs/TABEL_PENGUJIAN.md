# TABEL PENGUJIAN FITUR — Sistem Manajemen SARPRAS

**Institusi:** SMK Negeri 1 Boyolangu  
**Aplikasi:** Sistem Manajemen SARPRAS  
**Tanggal Pengujian:** 12 Februari 2026

---

## 1. Modul Autentikasi

| NO | SKENARIO PENGUJIAN | HASIL PENGUJIAN |
|----|---------------------|:---:|
| 1 | Siswa login menggunakan NISN (10 digit) dan password yang valid | ✅ PASS |
| 2 | Guru login menggunakan NIP (18 digit) dan password yang valid | ✅ PASS |
| 3 | Petugas login menggunakan username dan password yang valid | ✅ PASS |
| 4 | Admin login menggunakan username dan password yang valid | ✅ PASS |
| 5 | Login gagal jika username atau password salah → menampilkan pesan error | ✅ PASS |
| 6 | Login gagal jika akun belum diaktivasi (`is_activated = false`) | ✅ PASS |
| 7 | Siswa/Guru mendaftar akun baru (Register) | ✅ PASS |
| 8 | Validasi format NISN (harus 10 digit) saat register Siswa | ✅ PASS |
| 9 | Validasi format NIP (harus 18 digit) saat register Guru | ✅ PASS |
| 10 | Aktivasi akun berhasil setelah Admin mengaktivasi | ✅ PASS |
| 11 | Logout berhasil dan sesi dihapus | ✅ PASS |
| 12 | Ganti password dengan password lama yang benar | ✅ PASS |
| 13 | Ganti password gagal jika password lama tidak sesuai | ✅ PASS |

---

## 2. Modul Profil

| NO | SKENARIO PENGUJIAN | HASIL PENGUJIAN |
|----|---------------------|:---:|
| 14 | Menampilkan halaman profil dengan data user yang benar | ✅ PASS |
| 15 | Edit profil (ubah nama, foto profil) berhasil disimpan | ✅ PASS |
| 16 | Menampilkan statistik peminjaman pada halaman profil | ✅ PASS |

---

## 3. Modul Dashboard

| NO | SKENARIO PENGUJIAN | HASIL PENGUJIAN |
|----|---------------------|:---:|
| 17 | Dashboard menampilkan statistik total barang, peminjaman aktif, dan pengaduan | ✅ PASS |
| 18 | Dashboard hanya dapat diakses oleh Petugas dan Admin | ✅ PASS |
| 19 | Siswa/Guru tidak dapat mengakses halaman Dashboard | ✅ PASS |

---

## 4. Modul Kategori

| NO | SKENARIO PENGUJIAN | HASIL PENGUJIAN |
|----|---------------------|:---:|
| 20 | Menampilkan daftar kategori barang | ✅ PASS |
| 21 | Tambah kategori baru berhasil disimpan | ✅ PASS |
| 22 | Edit data kategori berhasil diperbarui | ✅ PASS |
| 23 | Hapus kategori berhasil | ✅ PASS |
| 24 | Validasi tambah kategori gagal jika nama kosong | ✅ PASS |

---

## 5. Modul Ruang

| NO | SKENARIO PENGUJIAN | HASIL PENGUJIAN |
|----|---------------------|:---:|
| 25 | Menampilkan daftar ruang/lokasi penyimpanan | ✅ PASS |
| 26 | Tambah ruang baru berhasil disimpan | ✅ PASS |
| 27 | Edit data ruang berhasil diperbarui | ✅ PASS |
| 28 | Hapus ruang berhasil | ✅ PASS |

---

## 6. Modul Katalog / Inventaris SARPRAS

| NO | SKENARIO PENGUJIAN | HASIL PENGUJIAN |
|----|---------------------|:---:|
| 29 | Menampilkan daftar seluruh barang inventaris | ✅ PASS |
| 30 | Filter dan pencarian barang berdasarkan nama, kode, atau kategori | ✅ PASS |
| 31 | Tambah barang baru (kode, nama, kategori, stok, foto) berhasil | ✅ PASS |
| 32 | Edit data barang berhasil diperbarui | ✅ PASS |
| 33 | Hapus barang (soft delete) berhasil | ✅ PASS |
| 34 | Lihat detail barang beserta daftar unit-unitnya | ✅ PASS |
| 35 | Tambah unit individual pada barang (kode unik otomatis) | ✅ PASS |
| 36 | Hapus unit individual dari barang | ✅ PASS |
| 37 | Restore barang dari Trash berhasil | ✅ PASS |
| 38 | Hapus permanen barang dari Trash berhasil | ✅ PASS |

---

## 7. Modul Peminjaman (Siswa/Guru)

| NO | SKENARIO PENGUJIAN | HASIL PENGUJIAN |
|----|---------------------|:---:|
| 39 | Menampilkan daftar barang yang tersedia untuk dipinjam (stok > 0) | ✅ PASS |
| 40 | Ajukan peminjaman dengan mengisi jumlah, tanggal, tujuan, dan lokasi | ✅ PASS |
| 41 | Validasi peminjaman gagal jika stok tidak mencukupi | ✅ PASS |
| 42 | Validasi peminjaman gagal jika durasi lebih dari 7 hari | ✅ PASS |
| 43 | Peminjaman tersimpan dengan status "Menunggu Persetujuan" | ✅ PASS |
| 44 | Menampilkan riwayat peminjaman milik sendiri | ✅ PASS |
| 45 | Menampilkan detail satu peminjaman | ✅ PASS |
| 46 | Cetak surat peminjaman dalam format PDF | ✅ PASS |
| 47 | Guru berhasil meminjam barang consumable/sekali pakai | ✅ PASS |
| 48 | Siswa tidak dapat meminjam barang consumable/sekali pakai | ✅ PASS |

---

## 8. Modul Peminjaman (Petugas/Admin)

| NO | SKENARIO PENGUJIAN | HASIL PENGUJIAN |
|----|---------------------|:---:|
| 49 | Menampilkan daftar semua peminjaman dari seluruh pengguna | ✅ PASS |
| 50 | Filter peminjaman berdasarkan status, tanggal, kelas, dan kode | ✅ PASS |
| 51 | Menyetujui peminjaman → status berubah menjadi "Disetujui" | ✅ PASS |
| 52 | Kode peminjaman dan QR Code di-generate setelah disetujui | ✅ PASS |
| 53 | Menolak peminjaman dengan alasan → status berubah menjadi "Ditolak" | ✅ PASS |
| 54 | Handover/serah terima: pilih unit spesifik dan upload foto kondisi awal | ✅ PASS |
| 55 | Status berubah menjadi "Dipinjam" dan stok berkurang setelah handover | ✅ PASS |
| 56 | Hapus data peminjaman (soft delete) berhasil | ✅ PASS |
| 57 | Restore peminjaman dari Trash berhasil | ✅ PASS |

---

## 9. Modul Pengembalian

| NO | SKENARIO PENGUJIAN | HASIL PENGUJIAN |
|----|---------------------|:---:|
| 58 | Scan QR Code peminjaman untuk memulai proses pengembalian | ✅ PASS |
| 59 | Input kode peminjaman secara manual untuk pengembalian | ✅ PASS |
| 60 | Menampilkan detail peminjaman dan unit yang dipinjam saat proses pengembalian | ✅ PASS |
| 61 | Catat pengembalian per unit dengan kondisi "Baik" | ✅ PASS |
| 62 | Catat pengembalian per unit dengan kondisi "Rusak" | ✅ PASS |
| 63 | Catat pengembalian per unit dengan kondisi "Hilang" | ✅ PASS |
| 64 | Upload foto kondisi pengembalian (opsional, tidak untuk barang hilang) | ✅ PASS |
| 65 | Stok barang diperbarui setelah pengembalian | ✅ PASS |
| 66 | Status peminjaman berubah menjadi "Dikembalikan" setelah pengembalian | ✅ PASS |
| 67 | Menampilkan daftar semua catatan pengembalian | ✅ PASS |
| 68 | Menampilkan detail satu catatan pengembalian | ✅ PASS |

---

## 10. Modul Barang Hilang

| NO | SKENARIO PENGUJIAN | HASIL PENGUJIAN |
|----|---------------------|:---:|
| 69 | Menampilkan daftar barang yang dilaporkan hilang saat pengembalian | ✅ PASS |
| 70 | Menampilkan detail kasus barang hilang | ✅ PASS |
| 71 | Selesaikan kasus barang hilang — ditemukan kembali | ✅ PASS |
| 72 | Selesaikan kasus barang hilang — ganti rugi | ✅ PASS |

---

## 11. Modul Pengaduan

| NO | SKENARIO PENGUJIAN | HASIL PENGUJIAN |
|----|---------------------|:---:|
| 73 | Buat pengaduan kerusakan → menampilkan barang dari riwayat peminjaman | ✅ PASS |
| 74 | Auto-fill informasi peminjaman saat memilih barang untuk pengaduan | ✅ PASS |
| 75 | Pengaduan tersimpan dengan status "Menunggu" | ✅ PASS |
| 76 | Menampilkan daftar pengaduan (pengguna: milik sendiri; petugas/admin: semua) | ✅ PASS |
| 77 | Menampilkan detail satu pengaduan | ✅ PASS |
| 78 | Tambah catatan/komentar pada pengaduan | ✅ PASS |
| 79 | Update status pengaduan: Menunggu → Diproses → Selesai | ✅ PASS |
| 80 | Hapus pengaduan (soft delete) berhasil | ✅ PASS |
| 81 | Restore pengaduan dari Trash berhasil | ✅ PASS |

---

## 12. Modul Maintenance

| NO | SKENARIO PENGUJIAN | HASIL PENGUJIAN |
|----|---------------------|:---:|
| 82 | Menampilkan daftar semua record maintenance | ✅ PASS |
| 83 | Tambah catatan maintenance baru (jenis, biaya, status) | ✅ PASS |
| 84 | Edit data maintenance berhasil diperbarui | ✅ PASS |
| 85 | Hapus catatan maintenance berhasil | ✅ PASS |
| 86 | Menampilkan detail satu maintenance record | ✅ PASS |

---

## 13. Modul Laporan

| NO | SKENARIO PENGUJIAN | HASIL PENGUJIAN |
|----|---------------------|:---:|
| 87 | Menampilkan riwayat kondisi per barang (Riwayat Kondisi Sarpras) | ✅ PASS |
| 88 | Menampilkan laporan kerusakan barang dan tindak lanjut | ✅ PASS |
| 89 | Menampilkan laporan Asset Health (kesehatan aset keseluruhan) | ✅ PASS |
| 90 | Menampilkan laporan statistik peminjaman dengan filter | ✅ PASS |
| 91 | Export laporan peminjaman ke file berhasil | ✅ PASS |
| 92 | Menampilkan Damage Analytics (hanya Admin) | ✅ PASS |
| 93 | Menampilkan Asset Lifecycle (hanya Admin) | ✅ PASS |

---

## 14. Modul Manajemen Pengguna (Khusus Admin)

| NO | SKENARIO PENGUJIAN | HASIL PENGUJIAN |
|----|---------------------|:---:|
| 94 | Menampilkan daftar seluruh pengguna sistem | ✅ PASS |
| 95 | Tambah user baru (nama, username, password, role, kelas/NIP) | ✅ PASS |
| 96 | Edit data user berhasil diperbarui | ✅ PASS |
| 97 | Hapus akun user berhasil | ✅ PASS |
| 98 | Menampilkan detail informasi satu user | ✅ PASS |
| 99 | Import user secara massal dari file Excel | ✅ PASS |
| 100 | Assign role (admin/petugas/pengguna/guru) ke user | ✅ PASS |
| 101 | Aktivasi akun pengguna agar bisa login | ✅ PASS |
| 102 | Deaktivasi akun pengguna agar tidak bisa login | ✅ PASS |
| 103 | Hanya Admin yang bisa mengakses modul manajemen pengguna | ✅ PASS |

---

## 15. Modul Activity Log (Khusus Admin)

| NO | SKENARIO PENGUJIAN | HASIL PENGUJIAN |
|----|---------------------|:---:|
| 104 | Menampilkan log semua aktivitas user di sistem | ✅ PASS |
| 105 | Filter activity log berdasarkan tanggal, user, dan jenis aksi | ✅ PASS |
| 106 | Menampilkan detail metadata aktivitas (data sebelum/sesudah) | ✅ PASS |
| 107 | Export data activity log ke file berhasil | ✅ PASS |
| 108 | Hanya Admin yang bisa mengakses Activity Log | ✅ PASS |

---

## 16. Modul Trash Terpadu

| NO | SKENARIO PENGUJIAN | HASIL PENGUJIAN |
|----|---------------------|:---:|
| 109 | Menampilkan semua data terhapus (Sarpras, Peminjaman, Pengaduan) dalam satu halaman | ✅ PASS |
| 110 | Restore data dari masing-masing tab di halaman Trash | ✅ PASS |
| 111 | Hapus permanen data dari halaman Trash | ✅ PASS |

---

## 17. Akses & Otorisasi

| NO | SKENARIO PENGUJIAN | HASIL PENGUJIAN |
|----|---------------------|:---:|
| 112 | Siswa tidak bisa mengakses halaman Dashboard | ✅ PASS |
| 113 | Siswa tidak bisa mengelola katalog barang (tambah/edit/hapus) | ✅ PASS |
| 114 | Siswa tidak bisa menyetujui atau menolak peminjaman | ✅ PASS |
| 115 | Siswa tidak bisa mencatat pengembalian di sistem | ✅ PASS |
| 116 | Guru tidak bisa mengakses halaman Dashboard | ✅ PASS |
| 117 | Petugas tidak bisa mengelola user (CRUD, import, assign role) | ✅ PASS |
| 118 | Petugas tidak bisa mengakses Activity Log | ✅ PASS |
| 119 | Petugas tidak bisa mengakses Damage Analytics dan Asset Lifecycle | ✅ PASS |
| 120 | Redirect ke halaman login jika belum login (session expired) | ✅ PASS |

---

## Ringkasan Pengujian

| Modul | Jumlah Skenario | PASS | FAIL |
|-------|:---:|:---:|:---:|
| Autentikasi | 13 | 13 | 0 |
| Profil | 3 | 3 | 0 |
| Dashboard | 3 | 3 | 0 |
| Kategori | 5 | 5 | 0 |
| Ruang | 4 | 4 | 0 |
| Katalog / Inventaris SARPRAS | 10 | 10 | 0 |
| Peminjaman (Siswa/Guru) | 10 | 10 | 0 |
| Peminjaman (Petugas/Admin) | 9 | 9 | 0 |
| Pengembalian | 11 | 11 | 0 |
| Barang Hilang | 4 | 4 | 0 |
| Pengaduan | 9 | 9 | 0 |
| Maintenance | 5 | 5 | 0 |
| Laporan | 7 | 7 | 0 |
| Manajemen Pengguna | 10 | 10 | 0 |
| Activity Log | 5 | 5 | 0 |
| Trash Terpadu | 3 | 3 | 0 |
| Akses & Otorisasi | 9 | 9 | 0 |
| **TOTAL** | **120** | **120** | **0** |

---

**Penguji:** ____________________  
**Tanggal:** 12 Februari 2026  
**Tanda Tangan:** ____________________

---

> **Keterangan:**  
> ✅ **PASS** = Fitur berjalan sesuai yang diharapkan  
> ❌ **FAIL** = Fitur tidak berjalan sesuai yang diharapkan
