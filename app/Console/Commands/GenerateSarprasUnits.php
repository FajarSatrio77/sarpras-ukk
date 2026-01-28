<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sarpras;
use App\Models\SarprasUnit;

class GenerateSarprasUnits extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sarpras:generate-units {--force : Regenerate units even if they exist}';

    /**
     * The console command description.
     */
    protected $description = 'Generate SarprasUnit records for existing Sarpras based on jumlah_stok';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $force = $this->option('force');
        
        $sarpras = Sarpras::with('units')->get();
        
        $this->info("Found {$sarpras->count()} sarpras items.");
        
        $created = 0;
        
        foreach ($sarpras as $item) {
            $existingUnits = $item->units->count();
            $neededUnits = $item->jumlah_stok;
            
            if ($force) {
                // Delete existing units that are 'tersedia' and regenerate
                $item->units()->where('status', 'tersedia')->delete();
                $existingUnits = $item->units()->count();
            }
            
            $toCreate = $neededUnits - $existingUnits;
            
            if ($toCreate > 0) {
                $this->line("Creating {$toCreate} units for: {$item->nama} ({$item->kode})");
                
                for ($i = 0; $i < $toCreate; $i++) {
                    SarprasUnit::create([
                        'sarpras_id' => $item->id,
                        'kode_unit' => SarprasUnit::generateKodeUnit($item->id),
                        'kondisi' => $item->kondisi,
                        'status' => 'tersedia',
                    ]);
                    $created++;
                }
            } else {
                $this->line("Skipping: {$item->nama} ({$item->kode}) - already has {$existingUnits} units");
            }
        }
        
        $this->info("Done! Created {$created} new unit records.");
        
        return Command::SUCCESS;
    }
}
