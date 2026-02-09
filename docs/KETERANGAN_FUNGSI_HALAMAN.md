# Penjelasan Fungsi Setiap Halaman UKK-SARPRAS

Setiap halaman dijelaskan dalam format: **Halaman [nama]: digunakan untuk [fungsi].**

---

**Halaman Utama (/)**

- **Halaman Utama:** Digunakan untuk mengarahkan pengguna: jika sudah login (Pengguna/Guru ke daftar pinjam, Admin/Petugas ke dashboard), jika belum login ke halaman login.

**Autentikasi**

- **Halaman Login:** Digunakan untuk autentikasi pengguna dengan NISN/NIP dan password.
- **Halaman Register:** Digunakan untuk pendaftaran akun baru (Pengguna/Guru perlu aktivasi oleh admin).
- **Halaman Aktivasi:** Digunakan untuk mengaktivasi akun pengguna/guru dengan kode dari admin.

**Umum (setelah login)**

- **Halaman Dashboard:** Digunakan untuk menampilkan ringkasan data peminjaman, pengembalian, dan pengaduan (Admin/Petugas).
- **Halaman Profile:** Digunakan untuk melihat dan mengubah data profil pengguna.
- **Halaman Ganti Password:** Digunakan untuk mengubah password akun.

**Manajemen User (Admin)**

- **Halaman Daftar User:** Digunakan untuk melihat, mencari, dan memfilter daftar user.
- **Halaman Tambah User:** Digunakan untuk menambah user baru ke sistem.
- **Halaman Edit User:** Digunakan untuk mengubah data user, role, dan status aktivasi.
- **Halaman Detail User:** Digunakan untuk melihat detail data user.
- **Halaman Import User:** Digunakan untuk mengimpor user dari file (misalnya Excel).
- **Halaman Activity Log:** Digunakan untuk melihat log aktivitas sistem dan mengekspornya.

**Manajemen Kategori & Ruang (Admin/Petugas)**

- **Halaman Daftar Kategori:** Digunakan untuk melihat daftar kategori sarpras.
- **Halaman Tambah/Edit Kategori:** Digunakan untuk menambah atau mengubah kategori sarpras.
- **Halaman Daftar Ruang:** Digunakan untuk melihat daftar ruang.
- **Halaman Tambah/Edit Ruang:** Digunakan untuk menambah atau mengubah ruang.

**Manajemen SARPRAS (Admin/Petugas)**

- **Halaman Daftar SARPRAS:** Digunakan untuk melihat daftar barang sarpras dengan filter dan pencarian.
- **Halaman Tambah SARPRAS:** Digunakan untuk menambah barang sarpras beserta unit.
- **Halaman Edit SARPRAS:** Digunakan untuk mengubah data barang dan mengelola unit.
- **Halaman Detail SARPRAS:** Digunakan untuk melihat detail barang dan daftar unit.
- **Halaman Trash SARPRAS:** Digunakan untuk melihat barang yang dihapus (soft delete) serta restore atau hapus permanen.
- **Halaman Trash (Unified):** Digunakan untuk melihat gabungan data terhapus (sarpras, peminjaman, pengaduan).

**Manajemen Peminjaman (Admin/Petugas)**

- **Halaman Daftar Peminjaman:** Digunakan untuk melihat daftar peminjaman, menyetujui/menolak, dan mengelola handover.
- **Halaman Trash Peminjaman:** Digunakan untuk melihat peminjaman yang telah dihapus.
- **Halaman Handover Peminjaman:** Digunakan untuk mencatat serah terima barang (pilih unit dan upload foto kondisi).

**Manajemen Pengembalian (Admin/Petugas)**

- **Halaman Daftar Pengembalian:** Digunakan untuk melihat daftar pengembalian dengan filter kondisi dan tanggal.
- **Halaman Scan Pengembalian:** Digunakan untuk memasukkan atau memindai kode peminjaman sebelum proses pengembalian.
- **Halaman Form Pengembalian:** Digunakan untuk mengisi tanggal kembali dan kondisi tiap unit (baik/rusak/hilang).
- **Halaman Detail Pengembalian:** Digunakan untuk melihat detail data pengembalian.

**Barang Hilang (Admin/Petugas)**

- **Halaman Daftar Barang Hilang:** Digunakan untuk melihat daftar kasus barang hilang.
- **Halaman Detail Barang Hilang:** Digunakan untuk melihat detail kasus dan menyelesaikan (ditemukan atau ganti rugi).

**Manajemen Pengaduan (Admin/Petugas)**

- **Halaman Trash Pengaduan:** Digunakan untuk melihat pengaduan yang telah dihapus.
- **Update Status/Catatan Pengaduan:** Digunakan untuk mengubah status pengaduan dan menambah catatan tanggapan.

**Maintenance (Admin/Petugas)**

- **Halaman Daftar Maintenance:** Digunakan untuk melihat daftar catatan maintenance.
- **Halaman Tambah/Edit/Detail Maintenance:** Digunakan untuk menambah, mengubah, atau melihat catatan maintenance.

**Laporan (Admin/Petugas)**

- **Halaman Riwayat Kondisi Sarpras:** Digunakan untuk melihat riwayat perubahan kondisi barang per sarpras.
- **Halaman Laporan Kerusakan:** Digunakan untuk melihat daftar kerusakan dari pengembalian dan tindak lanjut.
- **Halaman Laporan Asset Health:** Digunakan untuk melihat laporan kesehatan aset.
- **Halaman Laporan Damage Analytics:** Digunakan untuk melihat analisis kerusakan (Admin).
- **Halaman Laporan Asset Lifecycle:** Digunakan untuk melihat siklus hidup aset (Admin).

**Peminjaman (Pengguna/Guru)**

- **Halaman Daftar Sarpras untuk Pinjam:** Digunakan untuk melihat daftar barang yang dapat dipinjam dengan filter kategori dan pencarian.
- **Halaman Form Ajukan Peminjaman:** Digunakan untuk mengajukan peminjaman (jumlah, tanggal, tujuan, lokasi).
- **Halaman Riwayat Peminjaman:** Digunakan untuk melihat daftar peminjaman milik sendiri.
- **Halaman Form Buat Pengaduan:** Digunakan untuk membuat pengaduan terkait sarpras yang pernah dipinjam.

**Pengaduan & Peminjaman (Semua role)**

- **Halaman Daftar Pengaduan:** Digunakan untuk melihat daftar pengaduan (Pengguna hanya milik sendiri).
- **Halaman Detail Pengaduan:** Digunakan untuk melihat detail pengaduan dan menghapus jika milik sendiri.
- **Halaman Detail Peminjaman:** Digunakan untuk melihat detail peminjaman.
- **Halaman Cetak Peminjaman:** Digunakan untuk mencetak surat atau bukti peminjaman.
- **Hapus Peminjaman:** Digunakan untuk membatalkan atau menghapus peminjaman sesuai aturan aplikasi.
