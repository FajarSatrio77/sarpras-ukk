<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify role enum to include 'guru'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'petugas', 'guru', 'pengguna') NOT NULL DEFAULT 'pengguna'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'petugas', 'pengguna') NOT NULL DEFAULT 'pengguna'");
    }
};
