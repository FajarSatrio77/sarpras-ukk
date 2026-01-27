<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanUnit extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_unit';

    protected $fillable = [
        'peminjaman_id',
        'sarpras_unit_id',
        'kondisi_pinjam',
        'kondisi_kembali',
        'catatan_kembali',
    ];

    /**
     * Relasi: PeminjamanUnit milik satu peminjaman
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    /**
     * Relasi: PeminjamanUnit untuk satu unit sarpras
     */
    public function sarprasUnit()
    {
        return $this->belongsTo(SarprasUnit::class);
    }

    /**
     * Cek apakah unit sudah dikembalikan (ada kondisi kembali)
     */
    public function isDikembalikan()
    {
        return $this->kondisi_kembali !== null;
    }

    /**
     * Label kondisi pinjam dengan warna
     */
    public function getKondisiPinjamLabelAttribute()
    {
        return match($this->kondisi_pinjam) {
            'baik' => '<span class="badge bg-success">Baik</span>',
            'rusak_ringan' => '<span class="badge bg-warning text-dark">Rusak Ringan</span>',
            'rusak_berat' => '<span class="badge bg-danger">Rusak Berat</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }

    /**
     * Label kondisi kembali dengan warna
     */
    public function getKondisiKembaliLabelAttribute()
    {
        if (!$this->kondisi_kembali) {
            return '<span class="badge bg-secondary">Belum Dikembalikan</span>';
        }

        return match($this->kondisi_kembali) {
            'baik' => '<span class="badge bg-success">Baik</span>',
            'rusak_ringan' => '<span class="badge bg-warning text-dark">Rusak Ringan</span>',
            'rusak_berat' => '<span class="badge bg-danger">Rusak Berat</span>',
            'hilang' => '<span class="badge bg-dark">Hilang</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }
}
