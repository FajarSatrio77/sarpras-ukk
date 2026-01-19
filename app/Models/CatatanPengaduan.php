<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanPengaduan extends Model
{
    use HasFactory;

    protected $table = 'catatan_pengaduan';

    protected $fillable = [
        'pengaduan_id',
        'user_id',
        'catatan',
    ];

    /**
     * Relasi: Catatan milik satu pengaduan
     */
    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class);
    }

    /**
     * Relasi: Catatan dibuat oleh user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
