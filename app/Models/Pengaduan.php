<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduan';

    protected $fillable = [
        'user_id',
        'judul',
        'deskripsi',
        'lokasi',
        'jenis_sarpras',
        'foto',
        'status',
    ];

    /**
     * Relasi: Pengaduan milik satu user (pelapor)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Pengaduan memiliki banyak catatan tindak lanjut
     */
    public function catatan()
    {
        return $this->hasMany(CatatanPengaduan::class);
    }

    /**
     * Scope: Filter berdasarkan status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
