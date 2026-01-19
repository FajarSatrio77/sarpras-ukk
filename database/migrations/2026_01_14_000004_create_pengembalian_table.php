<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel untuk mencatat detail pengembalian dan kondisi alat
     */
    public function up(): void
    {
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->onDelete('cascade');
            $table->date('tgl_pengembalian');
            $table->enum('kondisi_alat', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang']);
            $table->text('deskripsi_kerusakan')->nullable();  // Detail kerusakan jika ada
            $table->string('foto')->nullable();               // Foto dokumentasi
            $table->text('catatan_petugas')->nullable();      // Catatan dari petugas
            $table->foreignId('diterima_oleh')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
