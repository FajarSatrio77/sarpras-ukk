<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add maintenance period to Categories (in months)
        // Schema::table('kategori_sarpras', function (Blueprint $table) {
        //     $table->integer('maintenance_period')->nullable()->default(null)->after('nama')
        //           ->comment('Jangka waktu maintenance rutin dalam bulan');
        // });

        // 2. Add maintenance tracking to Sarpras items
        Schema::table('sarpras', function (Blueprint $table) {
            $table->date('last_maintenance_date')->nullable()->after('kondisi');
            $table->date('next_maintenance_date')->nullable()->after('last_maintenance_date');
        });

        // 3. Create Maintenance Records table
        Schema::create('maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sarpras_id')->constrained('sarpras')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Technician/Reporter
            $table->date('tgl_maintenance');
            $table->enum('jenis', ['rutin', 'perbaikan', 'inspeksi']);
            $table->text('deskripsi');
            $table->decimal('biaya', 12, 2)->nullable();
            $table->enum('status', ['dijadwalkan', 'selesai', 'dibatalkan'])->default('dijadwalkan');
            $table->text('catatan')->nullable(); // Technician notes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_records');
        
        Schema::table('sarpras', function (Blueprint $table) {
            $table->dropColumn(['last_maintenance_date', 'next_maintenance_date']);
        });

        Schema::table('kategori_sarpras', function (Blueprint $table) {
            $table->dropColumn('maintenance_period');
        });
    }
};
