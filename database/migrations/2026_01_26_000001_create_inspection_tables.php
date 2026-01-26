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
        // Checklist Templates - Template inspeksi per kategori
        Schema::create('checklist_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_sarpras')->nullOnDelete();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Checklist Items - Item-item dalam template
        Schema::create('checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('checklist_templates')->cascadeOnDelete();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // Inspections - Record inspeksi (pre-borrow atau post-return)
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->cascadeOnDelete();
            $table->enum('tipe', ['pre_borrow', 'post_return']);
            $table->foreignId('inspector_id')->constrained('users');
            $table->text('catatan')->nullable();
            $table->string('foto_path')->nullable();
            $table->enum('kondisi_umum', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->boolean('ada_kerusakan_baru')->default(false);
            $table->timestamp('inspected_at')->useCurrent();
            $table->timestamps();
        });

        // Inspection Results - Hasil per item checklist
        Schema::create('inspection_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->cascadeOnDelete();
            $table->foreignId('checklist_item_id')->constrained()->cascadeOnDelete();
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_results');
        Schema::dropIfExists('inspections');
        Schema::dropIfExists('checklist_items');
        Schema::dropIfExists('checklist_templates');
    }
};
