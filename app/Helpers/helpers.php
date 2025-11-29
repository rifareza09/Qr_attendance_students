<?php

if (!function_exists('get_client_ip')) {
    /**
     * Get client IP address
     *
     * @return string
     */
    function get_client_ip(): string
    {
        return \App\Services\NetworkValidator::getClientIP();
    }
}

if (!function_exists('is_marked_today')) {
    /**
     * Check if student marked attendance today
     *
     * @param int $studentId
     * @return bool
     */
    function is_marked_today(int $studentId): bool
    {
        return \App\Models\Attendance::where('student_id', $studentId)
            ->whereDate('attendance_date', today())
            ->where('is_valid', true)
            ->exists();
    }
}

if (!function_exists('get_setting')) {
    /**
     * Get setting value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function get_setting(string $key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('set_setting')) {
    /**
     * Set setting value
     *
     * @param string $key
     * @param mixed $value
     * @param string|null $description
     * @return bool
     */
    function set_setting(string $key, $value, ?string $description = null): bool
    {
        return \App\Models\Setting::set($key, $value, $description);
    }
}

if (!function_exists('format_datetime')) {
    /**
     * Format datetime to human readable
     *
     * @param mixed $datetime
     * @param string $format
     * @return string
     */
    function format_datetime($datetime, string $format = 'd M Y H:i'): string
    {
        if (is_string($datetime)) {
            $datetime = \Carbon\Carbon::parse($datetime);
        }

        return $datetime ? $datetime->format($format) : '';
    }
}

if (!function_exists('is_wifi_valid')) {
    /**
     * Check if current WiFi is valid
     *
     * @return bool
     */
    function is_wifi_valid(): bool
    {
        return \App\Services\NetworkValidator::isValidWiFi();
    }
}

if (!function_exists('get_wifi_ssid')) {
    /**
     * Get current WiFi SSID
     *
     * @return string|null
     */
    function get_wifi_ssid(): ?string
    {
        return \App\Services\NetworkValidator::getWiFiSSID();
    }
}
