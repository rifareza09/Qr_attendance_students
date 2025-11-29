<?php

namespace App\Services;

use Illuminate\Support\Facades\Request;

class NetworkValidator
{
    /**
     * Get client IP address
     *
     * @return string
     */
    public static function getClientIP(): string
    {
        return Request::ip();
    }

    /**
     * Get WiFi SSID (Windows and Linux support)
     *
     * @return string|null
     */
    public static function getWiFiSSID(): ?string
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows
            $output = shell_exec('netsh wlan show interfaces');
            if ($output && preg_match('/SSID\s+:\s+(.+)/', $output, $matches)) {
                return trim($matches[1]);
            }
        } else {
            // Linux
            $output = shell_exec('iwgetid -r');
            if ($output) {
                return trim($output);
            }
        }

        return null;
    }

    /**
     * Check if current WiFi is valid
     *
     * @param string|null $validSSID
     * @return bool
     */
    public static function isValidWiFi(?string $validSSID = null): bool
    {
        // If WiFi check is disabled, always return true
        if (!config('attendance.enable_wifi_check')) {
            return true;
        }

        // Get valid SSID from settings or config
        if ($validSSID === null) {
            $validSSID = \App\Models\Setting::get(
                'valid_wifi_ssid',
                config('attendance.default_wifi')
            );
        }

        $currentSSID = self::getWiFiSSID();

        return $currentSSID === $validSSID;
    }

    /**
     * Validate IP address format
     *
     * @param string $ip
     * @return bool
     */
    public static function isValidIP(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Get network information
     *
     * @return array
     */
    public static function getNetworkInfo(): array
    {
        return [
            'ip' => self::getClientIP(),
            'wifi_ssid' => self::getWiFiSSID(),
            'is_valid_wifi' => self::isValidWiFi(),
            'user_agent' => Request::userAgent(),
            'timestamp' => now()->toDateTimeString(),
        ];
    }
}
