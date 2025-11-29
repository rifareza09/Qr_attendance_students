<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin user
        User::create([
            'name' => 'Admin',
            'email' => env('ADMIN_EMAIL', 'admin@attendance.test'),
            'username' => env('ADMIN_USERNAME', 'admin'),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'admin123')),
            'is_2fa_enabled' => false,
        ]);

        // Create default settings
        Setting::insert([
            [
                'setting_key' => 'valid_wifi_ssid',
                'setting_value' => env('DEFAULT_VALID_WIFI', 'YourValidWiFiSSID'),
                'description' => 'Valid WiFi network SSID for attendance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'qr_expiry_minutes',
                'setting_value' => env('QR_EXPIRY_MINUTES', 30),
                'description' => 'QR code expiry time in minutes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'enable_wifi_check',
                'setting_value' => env('ENABLE_WIFI_CHECK', true),
                'description' => 'Enable WiFi validation for attendance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'enable_2fa',
                'setting_value' => env('ENABLE_2FA', true),
                'description' => 'Enable Two-Factor Authentication for admins',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'enable_logging',
                'setting_value' => env('ENABLE_LOGGING', true),
                'description' => 'Enable activity logging',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('✓ Default admin user created (username: admin, password: admin123)');
        $this->command->info('✓ Default settings created');
        $this->command->warn('⚠️  Please change the admin password after first login!');
    }
}

