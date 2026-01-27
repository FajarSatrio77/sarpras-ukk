<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Pivot table untuk tracking unit mana yang dipinjam dalam setiap peminjaman
     */
    public function up(): void
    {
        Schema::create('peminjaman_unit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->onDelete('cascade');
            $table->foreignId('sarpras_unit_id')->constrained('sarpras_unit')->onDelete('cascade');
            $table->enum('kondisi_pinjam', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->enum('kondisi_kembali', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->nullable();
            $table->text('catatan_kembali')->nullable();  // Catatan saat pengembalian
            $table->timestamps();
            
            // Prevent duplicate: satu unit hanya bisa dipinjam sekali per peminjaman
            $table->unique(['peminjaman_id', 'sarpras_unit_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_unit');
    }
};
