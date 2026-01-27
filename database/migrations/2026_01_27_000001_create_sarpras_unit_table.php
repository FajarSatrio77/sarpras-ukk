<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel untuk menyimpan unit individual dari setiap sarpras
     */
    public function up(): void
    {
        Schema::create('sarpras_unit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sarpras_id')->constrained('sarpras')->onDelete('cascade');
            $table->string('kode_unit')->unique();  // Kode unik: TIK-001, TIK-002
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->default('baik');
            $table->enum('status', ['tersedia', 'dipinjam'])->default('tersedia');
            $table->text('catatan')->nullable();     // Catatan kondisi unit
            $table->timestamps();
            
            // Index untuk pencarian cepat
            $table->index(['sarpras_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sarpras_unit');
    }
};
