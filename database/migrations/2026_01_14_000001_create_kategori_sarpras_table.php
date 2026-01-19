<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel kategori untuk mengelompokkan jenis sarpras (Alat Lab, Buku, Perangkat TIK, dll)
     */
    public function up(): void
    {
        Schema::create('kategori_sarpras', function (Blueprint $table) {
            $table->id();
            $table->string('nama');           // Nama kategori: "Alat Lab", "Buku", "Perangkat TIK"
            $table->text('deskripsi')->nullable();  // Deskripsi kategori
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_sarpras');
    }
};
