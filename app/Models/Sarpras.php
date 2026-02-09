<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Ruang;

class Sarpras extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sarpras';

    protected $fillable = [
        'kode',
        'nama',
        'kategori_id',
        'ruang_id',
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
     * Relasi: Sarpras berada di satu ruang
     */
    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'ruang_id')->withDefault(['nama' => '-']);
    }

    /**
     * Relasi: Sarpras memiliki banyak peminjaman
     */
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * Scope: Filter sarpras yang tersedia untuk dipinjam
     * (memiliki unit dengan kondisi baik/rusak_ringan yang berstatus tersedia)
     */
    public function scopeTersedia($query)
    {
        return $query->whereHas('units', function($q) {
            $q->tersedia();
        });
    }

    /**
     * Get jumlah unit yang benar-benar bisa dipinjam saat ini
     * (Status: tersedia DAN Kondisi: baik/rusak_ringan)
     */
    public function getJumlahTersediaAttribute()
    {
        return $this->units()->tersedia()->count();
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
        $kategori = KategoriSarpras::find($kategoriId);
        $prefix = $kategori ? $kategori->kode : 'BRG';

        // Cari nomor urut terakhir dengan prefix kategori tersebut
        $lastKode = self::where('kode', 'like', $prefix . '-%')
            ->orderBy('kode', 'desc')
            ->value('kode');

        if ($lastKode) {
            // Extract angka dari kode terakhir
            // Mengambil bagian setelah karakter dash (-) terakhir
            $parts = explode('-', $lastKode);
            $lastNumber = (int) end($parts);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Format: PREFIX-001
        return strtoupper($prefix) . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
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
