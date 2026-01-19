<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriSarpras extends Model
{
    use HasFactory;

    protected $table = 'kategori_sarpras';

    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    /**
     * Relasi: Kategori memiliki banyak sarpras
     */
    public function sarpras()
    {
        return $this->hasMany(Sarpras::class, 'kategori_id');
    }
}
