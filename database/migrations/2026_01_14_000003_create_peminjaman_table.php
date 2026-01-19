<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel untuk mencatat peminjaman sarpras oleh pengguna
     */
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman')->unique();  // Kode unik: "PJM-20260114-001"
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('sarpras_id')->constrained('sarpras')->onDelete('cascade');
            $table->integer('jumlah');                    // Jumlah yang dipinjam
            $table->date('tgl_pinjam');                   // Tanggal mulai pinjam
            $table->date('tgl_kembali_rencana');          // Tanggal rencana kembali
            $table->date('tgl_kembali_aktual')->nullable(); // Tanggal aktual dikembalikan
            $table->text('tujuan');                       // Tujuan peminjaman
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'dipinjam', 'dikembalikan'])->default('menunggu');
            $table->text('catatan_persetujuan')->nullable(); // Catatan dari admin/petugas
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
