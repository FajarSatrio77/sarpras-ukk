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
        'browser',
        'device',
        'source',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
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
    public static function log(string $aksi, string $deskripsi, ?int $userId = null, ?array $metadata = null): self
    {
        $userAgent = request()->header('User-Agent', '');

        return self::create([
            'user_id' => $userId ?? auth()->id(),
            'aksi' => $aksi,
            'deskripsi' => $deskripsi,
            'ip_address' => request()->ip(),
            'browser' => self::detectBrowser($userAgent),
            'device' => self::detectDevice($userAgent),
            'source' => self::detectSource(),
            'metadata' => $metadata,
        ]);
    }

    /**
     * Deteksi nama browser dari User-Agent string
     */
    private static function detectBrowser(string $userAgent): string
    {
        if (empty($userAgent)) {
            return 'Unknown';
        }

        // Urutan penting: cek browser spesifik dulu sebelum yang umum
        if (preg_match('/Edg[e\/]?\s*(\d+)/i', $userAgent, $m)) {
            return 'Edge ' . $m[1];
        }
        if (preg_match('/OPR\/(\d+)/i', $userAgent, $m)) {
            return 'Opera ' . $m[1];
        }
        if (preg_match('/Brave/i', $userAgent)) {
            return 'Brave';
        }
        if (preg_match('/Vivaldi\/(\d+\.\d+)/i', $userAgent, $m)) {
            return 'Vivaldi ' . $m[1];
        }
        if (preg_match('/Chrome\/(\d+)/i', $userAgent, $m)) {
            return 'Chrome ' . $m[1];
        }
        if (preg_match('/Firefox\/(\d+)/i', $userAgent, $m)) {
            return 'Firefox ' . $m[1];
        }
        if (preg_match('/Safari\/(\d+)/i', $userAgent) && preg_match('/Version\/(\d+\.\d+)/i', $userAgent, $m)) {
            return 'Safari ' . $m[1];
        }
        if (preg_match('/MSIE\s(\d+)/i', $userAgent, $m) || preg_match('/Trident.*rv:(\d+)/i', $userAgent, $m)) {
            return 'IE ' . $m[1];
        }

        return 'Unknown';
    }

    /**
     * Deteksi jenis device dari User-Agent string
     */
    private static function detectDevice(string $userAgent): string
    {
        if (empty($userAgent)) {
            return 'Unknown';
        }

        $mobileKeywords = [
            'Mobile', 'Android', 'iPhone', 'iPad', 'iPod',
            'webOS', 'BlackBerry', 'Opera Mini', 'IEMobile',
            'Windows Phone', 'Kindle', 'Silk'
        ];

        foreach ($mobileKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false) {
                // Tablet detection
                if (stripos($userAgent, 'iPad') !== false || stripos($userAgent, 'Tablet') !== false) {
                    return 'Tablet';
                }
                return 'Mobile';
            }
        }

        return 'Desktop';
    }

    /**
     * Deteksi sumber kode (Controller@method) yang memanggil log
     */
    private static function detectSource(): string
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);

        foreach ($trace as $frame) {
            if (!isset($frame['class'])) {
                continue;
            }

            // Cari frame yang berasal dari Controller, bukan dari model sendiri
            $class = $frame['class'];
            if ($class === self::class) {
                continue;
            }

            // Ambil basename class (tanpa namespace)
            $className = class_basename($class);
            $method = $frame['function'] ?? 'unknown';

            return $className . '@' . $method;
        }

        return 'Unknown';
    }
}
