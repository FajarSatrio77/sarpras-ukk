<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel untuk pengaduan kerusakan/permasalahan sarpras
     */
    public function up(): void
    {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('judul');                      // Judul singkat pengaduan
            $table->text('deskripsi');                    // Deskripsi detail masalah
            $table->string('lokasi');                     // Lokasi sarpras bermasalah
            $table->string('jenis_sarpras');              // Jenis sarpras yang bermasalah
            $table->string('foto')->nullable();           // Foto bukti kerusakan
            $table->enum('status', ['menunggu', 'diproses', 'selesai'])->default('menunggu');
            $table->timestamps();
        });

        // Tabel untuk catatan tindak lanjut pengaduan
        Schema::create('catatan_pengaduan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengaduan_id')->constrained('pengaduan')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('catatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_pengaduan');
        Schema::dropIfExists('pengaduan');
    }
};
