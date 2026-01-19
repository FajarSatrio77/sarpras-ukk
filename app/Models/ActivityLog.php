<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_log';

    protected $fillable = [
        'user_id',
        'aksi',
        'deskripsi',
        'ip_address',
    ];

    /**
     * Relasi: Log milik satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper: Catat aktivitas baru
     */
    public static function log(string $aksi, string $deskripsi, ?int $userId = null): self
    {
        return self::create([
            'user_id' => $userId ?? auth()->id(),
            'aksi' => $aksi,
            'deskripsi' => $deskripsi,
            'ip_address' => request()->ip(),
        ]);
    }
}
