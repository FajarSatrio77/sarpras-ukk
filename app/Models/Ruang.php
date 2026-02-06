<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ruang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ruang';

    protected $fillable = [
        'nama',
        'keterangan',
    ];

    public function sarpras()
    {
        return $this->hasMany(Sarpras::class);
    }
}
