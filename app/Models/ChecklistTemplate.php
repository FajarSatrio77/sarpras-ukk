<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori_id',
        'sarpras_id',
        'nama',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Kategori yang terkait dengan template ini
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriSarpras::class, 'kategori_id');
    }

    /**
     * Sarpras yang terkait dengan template ini
     */
    public function sarpras()
    {
        return $this->belongsTo(Sarpras::class, 'sarpras_id');
    }

    /**
     * Item-item checklist dalam template
     */
    public function items()
    {
        return $this->hasMany(ChecklistItem::class, 'template_id')->orderBy('urutan');
    }

    /**
     * Scope untuk template aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Cari template untuk sarpras tertentu (hanya template spesifik untuk barang)
     */
    public static function findForSarpras($sarprasId, $kategoriId = null)
    {
        // Prioritas 1: Template spesifik untuk sarpras ini
        $template = self::active()
            ->with('items')
            ->where('sarpras_id', $sarprasId)
            ->first();
        
        // Tidak ada fallback ke kategori - template harus spesifik per barang
        // Jika ingin template kategori, gunakan findForKategori()
        
        return $template;
    }

    /**
     * Backward compatibility - findForKategori
     */
    public static function findForKategori($kategoriId)
    {
        // Cari template spesifik untuk kategori (dengan items)
        $template = self::active()
            ->with('items')
            ->whereNull('sarpras_id')
            ->where('kategori_id', $kategoriId)
            ->first();
        
        // Jika tidak ada, gunakan template global (kategori_id null)
        if (!$template) {
            $template = self::active()
                ->with('items')
                ->whereNull('sarpras_id')
                ->whereNull('kategori_id')
                ->first();
        }
        
        return $template;
    }
}
