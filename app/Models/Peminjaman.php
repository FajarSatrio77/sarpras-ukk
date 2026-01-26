<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'kode_peminjaman',
        'user_id',
        'sarpras_id',
        'jumlah',
        'tgl_pinjam',
        'tgl_kembali_rencana',
        'tgl_kembali_aktual',
        'tujuan',
        'status',
        'catatan_persetujuan',
        'disetujui_oleh',
    ];

    protected $casts = [
        'tgl_pinjam' => 'date',
        'tgl_kembali_rencana' => 'date',
        'tgl_kembali_aktual' => 'date',
    ];

    /**
     * Relasi: Peminjaman milik satu user (peminjam)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Peminjaman untuk satu sarpras
     */
    public function sarpras()
    {
        return $this->belongsTo(Sarpras::class);
    }

    /**
     * Relasi: Disetujui oleh user (admin/petugas)
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    /**
     * Relasi: Peminjaman memiliki satu pengembalian
     */
    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class);
    }

    /**
     * Generate kode peminjaman unik
     */
    public static function generateKode(): string
    {
        $tanggal = now()->format('Ymd');
        $lastPeminjaman = self::whereDate('created_at', today())->count() + 1;
        return 'PJM-' . $tanggal . '-' . str_pad($lastPeminjaman, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Scope: Filter berdasarkan status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Peminjaman aktif (disetujui atau sedang dipinjam)
     */
    public function scopeAktif($query)
    {
        return $query->whereIn('status', ['disetujui', 'dipinjam']);
    }

    /**
     * Relasi: Peminjaman memiliki banyak inspeksi
     */
    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }

    /**
     * Cek apakah sudah ada inspeksi pre-borrow
     */
    public function hasPreBorrowInspection()
    {
        return $this->inspections()->where('tipe', 'pre_borrow')->exists();
    }

    /**
     * Cek apakah sudah ada inspeksi post-return
     */
    public function hasPostReturnInspection()
    {
        return $this->inspections()->where('tipe', 'post_return')->exists();
    }
}
