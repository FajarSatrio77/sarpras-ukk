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
        $connection = config('database.default');

        if ($connection === 'mysql') {
            // Update kolom kondisi untuk menerima value 'maintenance'
            DB::statement("ALTER TABLE sarpras_unit MODIFY COLUMN kondisi ENUM('baik', 'rusak_ringan', 'rusak_berat', 'hilang', 'maintenance') DEFAULT 'baik'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = config('database.default');

        if ($connection === 'mysql') {
            // Sebelum rollback, pastikan data maintenance diubah dulu (misal jadi rusak_ringan atau baik) agar tidak error
            DB::table('sarpras_unit')->where('kondisi', 'maintenance')->update(['kondisi' => 'rusak_ringan']);

            DB::statement("ALTER TABLE sarpras_unit MODIFY COLUMN kondisi ENUM('baik', 'rusak_ringan', 'rusak_berat', 'hilang') DEFAULT 'baik'");
        }
    }
};
