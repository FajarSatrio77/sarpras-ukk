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
        // Prefix all existing unit codes with 'U-' if they don't already have it
        DB::statement("UPDATE sarpras_unit SET kode_unit = CONCAT('U-', kode_unit) WHERE kode_unit NOT LIKE 'U-%'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse is tricky because we might strip strictly valid U- prefixes, 
        // but for this specific fix, we can try to remove them.
        // Ideally we don't rollback data patches like this, but here is a best effort:
        DB::statement("UPDATE sarpras_unit SET kode_unit = SUBSTRING(kode_unit, 3) WHERE kode_unit LIKE 'U-%'");
    }
};
