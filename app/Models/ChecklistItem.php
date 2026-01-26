<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'nama',
        'deskripsi',
        'urutan',
    ];

    /**
     * Template parent
     */
    public function template()
    {
        return $this->belongsTo(ChecklistTemplate::class, 'template_id');
    }

    /**
     * Hasil inspeksi untuk item ini
     */
    public function results()
    {
        return $this->hasMany(InspectionResult::class);
    }
}
