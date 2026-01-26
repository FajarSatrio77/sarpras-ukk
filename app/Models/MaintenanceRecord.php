<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'sarpras_id',
        'user_id',
        'tgl_maintenance',
        'jenis',
        'deskripsi',
        'biaya',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tgl_maintenance' => 'date',
        'biaya' => 'decimal:2',
    ];

    /**
     * Relasi ke Sarpras
     */
    public function sarpras()
    {
        return $this->belongsTo(Sarpras::class);
    }

    /**
     * Relasi ke User (Teknisi/Pelapor)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
