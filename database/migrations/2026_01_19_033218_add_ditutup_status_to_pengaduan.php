<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan status 'ditutup' ke enum status pengaduan
     */
    public function up(): void
    {
        // MySQL: Alter ENUM to add new value
        DB::statement("ALTER TABLE pengaduan MODIFY status ENUM('menunggu', 'diproses', 'selesai', 'ditutup') DEFAULT 'menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum
        DB::statement("ALTER TABLE pengaduan MODIFY status ENUM('menunggu', 'diproses', 'selesai') DEFAULT 'menunggu'");
    }
};
