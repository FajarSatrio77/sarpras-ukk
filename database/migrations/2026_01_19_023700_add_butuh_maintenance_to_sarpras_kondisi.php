<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan status 'butuh_maintenance' ke enum kondisi sarpras
     */
    public function up(): void
    {
        // MySQL: Alter ENUM to add new value
        DB::statement("ALTER TABLE sarpras MODIFY kondisi ENUM('baik', 'rusak_ringan', 'rusak_berat', 'butuh_maintenance') DEFAULT 'baik'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum
        DB::statement("ALTER TABLE sarpras MODIFY kondisi ENUM('baik', 'rusak_ringan', 'rusak_berat') DEFAULT 'baik'");
    }
};
