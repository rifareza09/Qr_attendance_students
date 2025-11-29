<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SettingController;

// Student Routes
Route::get('/', [StudentController::class, 'index'])->name('student.index');
Route::post('/register', [StudentController::class, 'register'])->name('student.register');
Route::get('/scan-qr', [StudentController::class, 'showScanQr'])->name('student.scan-qr');
Route::post('/scan-qr', [AttendanceController::class, 'scanQr'])->name('attendance.scan');
Route::post('/mark-attendance', [AttendanceController::class, 'mark'])->name('attendance.mark');
Route::get('/dashboard/{id}', [StudentController::class, 'dashboard'])->name('student.dashboard');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Auth routes (guest only)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminController::class, 'login']);
    });

    // 2FA routes (authenticated but not verified)
    Route::middleware('auth')->group(function () {
        Route::get('/verify-2fa', [AdminController::class, 'show2FA'])->name('verify-2fa');
        Route::post('/verify-2fa', [AdminController::class, 'verify2FA']);
    });

    // Protected routes (authenticated and 2FA verified)
    Route::middleware(['auth', 'App\Http\Middleware\TwoFactorAuth'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

        // Student management
        Route::get('/students/{id}/edit', [AdminController::class, 'editStudent'])->name('students.edit');
        Route::put('/students/{id}', [AdminController::class, 'updateStudent'])->name('students.update');
        Route::post('/students/{id}/toggle', [AdminController::class, 'deactivateStudent'])->name('students.toggle');
        Route::get('/students/{id}/credentials', [AdminController::class, 'viewCredentials'])->name('students.credentials');

        // Attendance management
        Route::post('/attendance/{id}/invalidate', [AdminController::class, 'invalidateAttendance'])->name('attendance.invalidate');
        Route::get('/export', [AdminController::class, 'export'])->name('export');

        // QR Code management
        Route::post('/qrcode/generate', [AdminController::class, 'generateQrCode'])->name('qrcode.generate');

        // Settings
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});

