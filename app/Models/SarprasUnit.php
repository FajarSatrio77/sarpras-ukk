<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SarprasUnit extends Model
{
    use HasFactory;

    protected $table = 'sarpras_unit';

    protected $fillable = [
        'sarpras_id',
        'kode_unit',
        'kondisi',
        'status',
        'catatan',
    ];

    /**
     * Relasi: Unit milik satu sarpras
     */
    public function sarpras()
    {
        return $this->belongsTo(Sarpras::class);
    }

    /**
     * Relasi: Unit memiliki banyak peminjaman (melalui pivot)
     */
    public function peminjamanUnits()
    {
        return $this->hasMany(PeminjamanUnit::class);
    }

    /**
     * Scope: Filter unit yang tersedia
     */
    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia')
                     ->where('kondisi', '!=', 'hilang');
    }

    /**
     * Scope: Filter unit yang sedang dipinjam
     */
    public function scopeDipinjam($query)
    {
        return $query->where('status', 'dipinjam');
    }

    /**
     * Scope: Filter berdasarkan kondisi
     */
    public function scopeKondisi($query, $kondisi)
    {
        return $query->where('kondisi', $kondisi);
    }

    /**
     * Generate kode unit otomatis berdasarkan sarpras
     * Format: PREFIX-XXX (misal: TIK-001, TIK-002)
     */
    public static function generateKodeUnit($sarprasId, $unitNumber = null)
    {
        $sarpras = Sarpras::with('kategori')->find($sarprasId);
        
        if (!$sarpras) {
            return null;
        }

        // Gunakan kode kategori sebagai prefix
        $prefix = $sarpras->kategori && $sarpras->kategori->kode 
            ? strtoupper($sarpras->kategori->kode) 
            : strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $sarpras->kategori->nama ?? 'XXX'), 0, 3));
        
        // Pad prefix ke 3 karakter
        $prefix = str_pad($prefix, 3, 'X');

        if ($unitNumber !== null) {
            return $prefix . '-' . str_pad($unitNumber, 3, '0', STR_PAD_LEFT);
        }

        // Cari nomor urut terakhir untuk prefix ini
        $lastKode = self::where('kode_unit', 'like', $prefix . '-%')
            ->orderBy('kode_unit', 'desc')
            ->value('kode_unit');

        if ($lastKode) {
            $lastNumber = (int) substr($lastKode, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Label kondisi dengan warna
     */
    public function getKondisiLabelAttribute()
    {
        return match($this->kondisi) {
            'baik' => '<span class="badge bg-success">Baik</span>',
            'rusak_ringan' => '<span class="badge bg-warning text-dark">Rusak Ringan</span>',
            'rusak_berat' => '<span class="badge bg-danger">Rusak Berat</span>',
            'hilang' => '<span class="badge bg-dark">Hilang</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }

    /**
     * Label status dengan warna
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'tersedia' => '<span class="badge bg-success">Tersedia</span>',
            'dipinjam' => '<span class="badge bg-info">Dipinjam</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }

    /**
     * Cek apakah unit bisa dipinjam
     */
    public function canBeBorrowed()
    {
        return $this->status === 'tersedia' && 
               in_array($this->kondisi, ['baik', 'rusak_ringan']);
    }
}
