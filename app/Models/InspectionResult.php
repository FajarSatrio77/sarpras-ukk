<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id',
        'checklist_item_id',
        'kondisi',
        'catatan',
    ];

    /**
     * Inspeksi parent
     */
    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }

    /**
     * Item checklist terkait
     */
    public function checklistItem()
    {
        return $this->belongsTo(ChecklistItem::class);
    }

    /**
     * Label kondisi yang readable
     */
    public function getKondisiLabelAttribute()
    {
        return match($this->kondisi) {
            'baik' => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat',
            default => $this->kondisi,
        };
    }

    /**
     * Badge class untuk kondisi
     */
    public function getKondisiBadgeAttribute()
    {
        return match($this->kondisi) {
            'baik' => 'badge-success',
            'rusak_ringan' => 'badge-warning',
            'rusak_berat' => 'badge-danger',
            default => 'badge-secondary',
        };
    }
}
