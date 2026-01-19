<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sarpras extends Model
{
    use HasFactory;

    protected $table = 'sarpras';

    protected $fillable = [
        'kode',
        'nama',
        'kategori_id',
        'lokasi',
        'jumlah_stok',
        'kondisi',
        'deskripsi',
        'foto',
    ];

    /**
     * Relasi: Sarpras milik satu kategori
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriSarpras::class, 'kategori_id');
    }

    /**
     * Relasi: Sarpras memiliki banyak peminjaman
     */
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * Scope: Filter sarpras yang tersedia (stok > 0)
     */
    public function scopeTersedia($query)
    {
        return $query->where('jumlah_stok', '>', 0);
    }

    /**
     * Scope: Filter berdasarkan kondisi
     */
    public function scopeKondisi($query, $kondisi)
    {
        return $query->where('kondisi', $kondisi);
    }
}
