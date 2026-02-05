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
        // Mendapatkan koneksi database saat ini untuk mengecek driver
        $connection = config('database.default');

        if ($connection === 'mysql') {
            // Untuk MySQL, kita menggunakan raw SQL untuk mengubah kolom ENUM
            DB::statement("ALTER TABLE sarpras_unit MODIFY COLUMN status ENUM('tersedia', 'dipinjam', 'maintenance') DEFAULT 'tersedia'");
        } else {
            // Fallback untuk database lain jika diperlukan (misal SQLite tidak support enum native dengan cara sama)
            // Namun karena error log menunjukkan MySQL, fokus utama di atas.
            // Jika menggunakan SQLite (untuk testing), kolom biasanya VARCHAR/TEXT jadi tidak masalah.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = config('database.default');

        if ($connection === 'mysql') {
            // Kembalikan ke definisi semula (hati-hati jika ada data 'maintenance' akan error/truncated)
            // Sebaiknya update data dulu sebelum rollback jika memungkinkan, tapi untuk safety kita biarkan saja
            // atau ubah maintenance jadi tersedia dulu.
            
            DB::table('sarpras_unit')->where('status', 'maintenance')->update(['status' => 'tersedia']);
            
            DB::statement("ALTER TABLE sarpras_unit MODIFY COLUMN status ENUM('tersedia', 'dipinjam') DEFAULT 'tersedia'");
        }
    }
};
