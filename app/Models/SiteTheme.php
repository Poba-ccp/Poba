<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteTheme extends Model
{
    protected $fillable = ['setting_key', 'setting_value', 'setting_group'];

    // ── Get all settings as a key => value array (cached for performance) ──
    public static function getAll(): array
    {
        return Cache::remember('site_theme_settings', 3600, function () {
            return self::pluck('setting_value', 'setting_key')->toArray();
        });
    }

    // ── Save multiple settings at once and clear cache ──
    public static function saveAll(array $settings): void
    {
        foreach ($settings as $key => $value) {
            self::updateOrCreate(
                ['setting_key' => $key],
                ['setting_value' => $value]
            );
        }
        Cache::forget('site_theme_settings');
    }

    // ── Get a single setting with a default fallback ──
    public static function get(string $key, string $default = ''): string
    {
        $all = self::getAll();
        return $all[$key] ?? $default;
    }
}
