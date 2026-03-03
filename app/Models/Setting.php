<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function getValue(string $key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function setValue(string $key, $value)
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting_{$key}");
    }

    public static function getValues(): array
    {
        return Cache::remember('all_settings', 3600, function () {
            return static::all()->pluck('value', 'key')->toArray();
        });
    }

    public static function clearCache()
    {
        Cache::forget('all_settings');
    }
}
