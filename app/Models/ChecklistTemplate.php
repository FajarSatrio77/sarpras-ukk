<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori_id',
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
     * Cari template untuk kategori tertentu atau template global
     */
    public static function findForKategori($kategoriId)
    {
        // Cari template spesifik untuk kategori
        $template = self::active()->where('kategori_id', $kategoriId)->first();
        
        // Jika tidak ada, gunakan template global (kategori_id null)
        if (!$template) {
            $template = self::active()->whereNull('kategori_id')->first();
        }
        
        return $template;
    }
}
