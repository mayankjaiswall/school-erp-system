<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['group', 'key', 'value'];

    public const CACHE_KEY = 'app_settings';

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, $default = null)
    {
        return static::all_cached()->get($key, $default);
    }

    /**
     * Get all settings of a group as [key => value].
     */
    public static function group(string $group): array
    {
        return static::query()
            ->where('group', $group)
            ->pluck('value', 'key')
            ->all();
    }

    /**
     * Create or update a setting.
     */
    public static function set(string $key, $value, string $group = 'general'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );

        Cache::forget(static::CACHE_KEY);
    }

    /**
     * Set many settings at once for a group.
     */
    public static function setMany(array $values, string $group = 'general'): void
    {
        foreach ($values as $key => $value) {
            static::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => $group]
            );
        }

        Cache::forget(static::CACHE_KEY);
    }

    /**
     * All settings as a cached [key => value] collection.
     */
    public static function all_cached()
    {
        return Cache::rememberForever(static::CACHE_KEY, function () {
            return static::query()->pluck('value', 'key');
        });
    }
}
