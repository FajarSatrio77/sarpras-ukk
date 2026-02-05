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
            // Tambahkan status 'hilang' dan 'rusak'
            DB::statement("ALTER TABLE sarpras_unit MODIFY COLUMN status ENUM('tersedia', 'dipinjam', 'maintenance', 'hilang', 'rusak') DEFAULT 'tersedia'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = config('database.default');

        if ($connection === 'mysql') {
            // Kembalikan ke 'maintenance' atau 'tersedia' jika ada data hilang/rusak sebelum rollback
            DB::table('sarpras_unit')->whereIn('status', ['hilang', 'rusak'])->update(['status' => 'maintenance']);
            
            DB::statement("ALTER TABLE sarpras_unit MODIFY COLUMN status ENUM('tersedia', 'dipinjam', 'maintenance') DEFAULT 'tersedia'");
        }
    }
};
