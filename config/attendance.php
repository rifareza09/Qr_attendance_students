<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Encryption Settings
    |--------------------------------------------------------------------------
    */
    'encryption_key' => env('ENCRYPTION_KEY', 'default-key-change-this'),
    'encryption_method' => env('ENCRYPTION_METHOD', 'AES-256-CBC'),

    /*
    |--------------------------------------------------------------------------
    | Network & WiFi Settings
    |--------------------------------------------------------------------------
    */
    'default_wifi' => env('DEFAULT_VALID_WIFI', 'YourValidWiFiSSID'),
    'enable_wifi_check' => env('ENABLE_WIFI_CHECK', true),

    /*
    |--------------------------------------------------------------------------
    | Testing Mode
    |--------------------------------------------------------------------------
    | When enabled, IP address checking will be disabled to allow
    | multiple registrations from the same device for testing purposes.
    | WARNING: Always set to false in production!
    */
    'testing_mode' => env('TESTING_MODE', false),

    /*
    |--------------------------------------------------------------------------
    | Session Settings
    |--------------------------------------------------------------------------
    */
    'session_timeout' => env('SESSION_TIMEOUT', 1800), // 30 minutes

    /*
    |--------------------------------------------------------------------------
    | Two-Factor Authentication
    |--------------------------------------------------------------------------
    */
    'enable_2fa' => env('ENABLE_2FA', true),
    'google2fa_window' => env('GOOGLE2FA_WINDOW', 1),

    /*
    |--------------------------------------------------------------------------
    | Activity Logging
    |--------------------------------------------------------------------------
    */
    'enable_logging' => env('ENABLE_LOGGING', true),

    /*
    |--------------------------------------------------------------------------
    | QR Code Settings
    |--------------------------------------------------------------------------
    */
    'qr_code' => [
        'size' => env('QR_CODE_SIZE', 300),
        'format' => env('QR_CODE_FORMAT', 'png'),
        'margin' => env('QR_CODE_MARGIN', 2),
        'expiry_minutes' => env('QR_EXPIRY_MINUTES', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage Paths
    |--------------------------------------------------------------------------
    */
    'storage' => [
        'qrcodes' => env('QR_CODE_STORAGE_PATH', 'qrcodes'),
        'exports' => env('EXPORT_STORAGE_PATH', 'exports'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */
    'rate_limit' => [
        'login' => env('LOGIN_RATE_LIMIT', 5),
        'attendance' => env('ATTENDANCE_RATE_LIMIT', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */
    'pagination' => [
        'attendance' => env('ATTENDANCE_PER_PAGE', 50),
        'students' => env('STUDENTS_PER_PAGE', 50),
        'logs' => env('LOGS_PER_PAGE', 100),
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    */
    'features' => [
        'student_dashboard' => env('ENABLE_STUDENT_DASHBOARD', true),
        'export_json' => env('ENABLE_EXPORT_JSON', true),
        'export_csv' => env('ENABLE_EXPORT_CSV', true),
        'export_excel' => env('ENABLE_EXPORT_EXCEL', true),
    ],
];
