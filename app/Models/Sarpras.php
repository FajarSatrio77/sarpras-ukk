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
        'sekali_pakai',
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

    /**
     * Scope: Filter barang sekali pakai (consumable)
     */
    public function scopeSekalaiPakai($query, $value = true)
    {
        return $query->where('sekali_pakai', $value);
    }

    /**
     * Scope: Filter sarpras yang tersedia untuk user berdasarkan role
     * - Guru: bisa lihat semua barang termasuk sekali pakai
     * - Pengguna biasa: hanya barang non-sekali pakai
     */
    public function scopeTersediaUntukUser($query, $user)
    {
        // Jika user bukan guru, exclude barang sekali pakai
        if (!$user->canBorrowConsumable()) {
            $query->where('sekali_pakai', false);
        }
        return $query;
    }

    /**
     * Generate kode barang otomatis berdasarkan kategori
     * Format: singkatan kategori (dari field kode) + 3 angka urut
     * Contoh: ELK-001, KOM-002, OLR-003
     */
    public static function generateKode($kategoriId)
    {
        $kategori = \App\Models\KategoriSarpras::find($kategoriId);
        
        if (!$kategori) {
            return null;
        }

        // Gunakan kode/singkatan dari kategori, atau fallback ke 3 huruf pertama nama
        $prefix = $kategori->kode 
            ? strtoupper($kategori->kode)
            : strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $kategori->nama), 0, 3));
        
        // Jika kurang dari 3 huruf, pad dengan 'X'
        $prefix = str_pad($prefix, 3, 'X');

        // Cari nomor urut terakhir untuk kategori ini
        $lastKode = self::where('kategori_id', $kategoriId)
            ->where('kode', 'like', $prefix . '-%')
            ->orderBy('kode', 'desc')
            ->value('kode');

        if ($lastKode) {
            // Extract angka dari kode terakhir
            $lastNumber = (int) substr($lastKode, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Format: XXX-001
        return $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Relasi: Sarpras memiliki banyak unit individual
     */
    public function units()
    {
        return $this->hasMany(SarprasUnit::class);
    }

    /**
     * Get unit yang tersedia untuk dipinjam
     */
    public function unitsTersedia()
    {
        return $this->units()->tersedia();
    }

    /**
     * Get jumlah unit tersedia
     */
    public function getJumlahUnitTersediaAttribute()
    {
        return $this->units()->tersedia()->count();
    }
}
