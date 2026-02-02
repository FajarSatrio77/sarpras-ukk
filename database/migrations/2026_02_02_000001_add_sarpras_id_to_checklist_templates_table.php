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
        Schema::table('checklist_templates', function (Blueprint $table) {
            $table->foreignId('sarpras_id')->nullable()->after('kategori_id')->constrained('sarpras')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checklist_templates', function (Blueprint $table) {
            $table->dropForeign(['sarpras_id']);
            $table->dropColumn('sarpras_id');
        });
    }
};
