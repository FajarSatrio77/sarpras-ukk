<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'peminjaman_id',
        'tipe',
        'inspector_id',
        'catatan',
        'foto_path',
        'kondisi_umum',
        'ada_kerusakan_baru',
        'inspected_at',
    ];

    protected $casts = [
        'ada_kerusakan_baru' => 'boolean',
        'inspected_at' => 'datetime',
    ];

    const TIPE_PRE_BORROW = 'pre_borrow';
    const TIPE_POST_RETURN = 'post_return';

    /**
     * Peminjaman terkait
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    /**
     * Petugas yang melakukan inspeksi
     */
    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    /**
     * Hasil per item checklist
     */
    public function results()
    {
        return $this->hasMany(InspectionResult::class);
    }

    /**
     * Scope untuk inspeksi pre-borrow
     */
    public function scopePreBorrow($query)
    {
        return $query->where('tipe', self::TIPE_PRE_BORROW);
    }

    /**
     * Scope untuk inspeksi post-return
     */
    public function scopePostReturn($query)
    {
        return $query->where('tipe', self::TIPE_POST_RETURN);
    }

    /**
     * Bandingkan dengan inspeksi lain dan deteksi kerusakan baru
     */
    public function compareWith(Inspection $other)
    {
        $comparison = [];
        
        foreach ($this->results as $result) {
            $otherResult = $other->results->where('checklist_item_id', $result->checklist_item_id)->first();
            
            $comparison[] = [
                'item' => $result->checklistItem,
                'pre_kondisi' => $otherResult ? $otherResult->kondisi : null,
                'post_kondisi' => $result->kondisi,
                'berubah' => $otherResult && $otherResult->kondisi !== $result->kondisi,
                'memburuk' => $this->isWorse($otherResult?->kondisi, $result->kondisi),
            ];
        }
        
        return $comparison;
    }

    /**
     * Cek apakah kondisi memburuk
     */
    private function isWorse($before, $after)
    {
        $order = ['baik' => 0, 'rusak_ringan' => 1, 'rusak_berat' => 2];
        
        if (!$before || !$after) return false;
        
        return ($order[$after] ?? 0) > ($order[$before] ?? 0);
    }
}
