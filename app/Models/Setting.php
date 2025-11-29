<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'setting_key',
        'setting_value',
        'description',
    ];

    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('setting_key', $key)->first();

        return $setting ? $setting->setting_value : $default;
    }

    /**
     * Set a setting value.
     *
     * @param string $key
     * @param mixed $value
     * @param string|null $description
     * @return bool
     */
    public static function set(string $key, $value, ?string $description = null): bool
    {
        return static::updateOrCreate(
            ['setting_key' => $key],
            [
                'setting_value' => $value,
                'description' => $description ?? ''
            ]
        ) !== null;
    }

    /**
     * Get multiple settings as an array.
     *
     * @param array $keys
     * @return array
     */
    public static function getMultiple(array $keys): array
    {
        $settings = static::whereIn('setting_key', $keys)->get();

        $result = [];
        foreach ($settings as $setting) {
            $result[$setting->setting_key] = $setting->setting_value;
        }

        return $result;
    }

    /**
     * Get all settings as key-value pairs.
     *
     * @return array
     */
    public static function getAllSettings(): array
    {
        return static::query()
            ->pluck('setting_value', 'setting_key')
            ->toArray();
    }
}
