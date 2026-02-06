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
        // 1. Add ruang_id column
        Schema::table('sarpras', function (Blueprint $table) {
            $table->foreignId('ruang_id')->nullable()->after('kategori_id')->constrained('ruang')->onDelete('set null');
        });

        // 2. Migrate existing data
        $sarprasItems = DB::table('sarpras')->whereNotNull('lokasi')->get();
        foreach ($sarprasItems as $item) {
            if (!empty($item->lokasi)) {
                // Find or create Ruang
                $ruang = DB::table('ruang')->where('nama', $item->lokasi)->first();
                if (!$ruang) {
                    $ruangId = DB::table('ruang')->insertGetId([
                        'nama' => $item->lokasi,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $ruangId = $ruang->id;
                }

                // Update Sarpras
                DB::table('sarpras')->where('id', $item->id)->update(['ruang_id' => $ruangId]);
            }
        }

        // 3. Drop lokasi column
        Schema::table('sarpras', function (Blueprint $table) {
             $table->dropColumn('lokasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sarpras', function (Blueprint $table) {
            $table->string('lokasi')->nullable()->after('ruang_id');
        });

        // Restore data
        $results = DB::table('sarpras')
            ->join('ruang', 'sarpras.ruang_id', '=', 'ruang.id')
            ->select('sarpras.id', 'ruang.nama')
            ->get();

        foreach ($results as $row) {
            DB::table('sarpras')->where('id', $row->id)->update(['lokasi' => $row->nama]);
        }

        Schema::table('sarpras', function (Blueprint $table) {
            $table->dropForeign(['ruang_id']);
            $table->dropColumn('ruang_id');
        });
    }
};
