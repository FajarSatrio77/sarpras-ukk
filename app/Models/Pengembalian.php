<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalian';

    protected $fillable = [
        'peminjaman_id',
        'tgl_pengembalian',
        'kondisi_alat',
        'deskripsi_kerusakan',
        'foto',
        'catatan_petugas',
        'diterima_oleh',
    ];

    protected $casts = [
        'tgl_pengembalian' => 'date',
    ];

    /**
     * Relasi: Pengembalian milik satu peminjaman
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    /**
     * Relasi: Diterima oleh user (admin/petugas)
     */
    public function penerima()
    {
        return $this->belongsTo(User::class, 'diterima_oleh');
    }
}
