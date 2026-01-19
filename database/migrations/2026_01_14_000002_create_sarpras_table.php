<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel utama untuk menyimpan data sarana prasarana sekolah
     */
    public function up(): void
    {
        Schema::create('sarpras', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();      // Kode unik alat: "PRJ-001", "LPT-002"
            $table->string('nama');                // Nama alat: "Proyektor Epson", "Laptop ASUS"
            $table->foreignId('kategori_id')->constrained('kategori_sarpras')->onDelete('cascade');
            $table->string('lokasi');              // Lokasi penyimpanan: "Lab RPL", "Perpustakaan"
            $table->integer('jumlah_stok');        // Jumlah stok yang tersedia
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->text('deskripsi')->nullable(); // Deskripsi tambahan
            $table->string('foto')->nullable();    // Path foto alat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sarpras');
    }
};
