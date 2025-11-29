<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Attendance;
use App\Models\QrCode;
use App\Models\Setting;
use Exception;

class AttendanceService
{
    /**
     * Mark attendance by IP address
     *
     * @param string $ip
     * @return array
     */
    public static function markByIP(string $ip): array
    {
        try {
            // Find student by IP
            $student = Student::where('student_ip', $ip)
                ->where('is_active', true)
                ->first();

            if (!$student) {
                return [
                    'success' => false,
                    'message' => 'Student not registered or inactive'
                ];
            }

            // Check WiFi validation
            if (config('attendance.enable_wifi_check') && !NetworkValidator::isValidWiFi()) {
                return [
                    'success' => false,
                    'message' => 'You must be connected to valid Wi-Fi network'
                ];
            }

            // Check if already marked today
            if (self::isMarkedToday($student->id)) {
                return [
                    'success' => false,
                    'message' => 'Attendance already marked today'
                ];
            }

            // Generate QR code
            $qrResult = QrCodeService::generate($student->id, 'Auto-' . now()->format('Y-m-d'));

            if (!$qrResult['success']) {
                return $qrResult;
            }

            // Mark attendance
            return self::markWithQR($qrResult['qr_code'], $ip);

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error marking attendance: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Mark attendance with QR code
     *
     * @param string $qrCodeHash
     * @param string $ip
     * @return array
     */
    public static function markWithQR(string $qrCodeHash, string $ip): array
    {
        try {
            // Validate QR code
            $validation = QrCodeService::validate($qrCodeHash);

            if (!$validation['success']) {
                return $validation;
            }

            $studentId = $validation['student_id'];

            // Check if already marked today
            if (self::isMarkedToday($studentId)) {
                return [
                    'success' => false,
                    'message' => 'Attendance already marked today'
                ];
            }

            // Get WiFi SSID
            $wifiSsid = NetworkValidator::getWiFiSSID();

            // Create attendance record
            $attendance = Attendance::create([
                'student_id' => $studentId,
                'student_ip' => $ip,
                'qr_code' => $qrCodeHash,
                'attendance_date' => now()->toDateString(),
                'attendance_time' => now()->toTimeString(),
                'session_name' => $validation['session_name'],
                'wifi_ssid' => $wifiSsid,
                'is_valid' => true,
                'marked_at' => now(),
            ]);

            // Mark QR code as used
            QrCodeService::markAsUsed($qrCodeHash);

            // Log activity
            if (config('attendance.enable_logging')) {
                activity()
                    ->causedBy($studentId)
                    ->performedOn($attendance)
                    ->withProperties([
                        'ip' => $ip,
                        'wifi_ssid' => $wifiSsid,
                    ])
                    ->log('Attendance marked');
            }

            return [
                'success' => true,
                'message' => 'Attendance marked successfully',
                'attendance_id' => $attendance->id,
                'date' => $attendance->attendance_date,
                'time' => $attendance->attendance_time,
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error marking attendance: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check if student has marked attendance today
     *
     * @param int $studentId
     * @return bool
     */
    public static function isMarkedToday(int $studentId): bool
    {
        return Attendance::where('student_id', $studentId)
            ->where('is_valid', true)
            ->whereDate('attendance_date', today())
            ->exists();
    }

    /**
     * Get attendance statistics
     *
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @return array
     */
    public static function getStatistics(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $query = Attendance::where('is_valid', true);

        if ($dateFrom && $dateTo) {
            $query->whereBetween('attendance_date', [$dateFrom, $dateTo]);
        }

        $totalAttendances = $query->count();
        $uniqueStudents = $query->distinct('student_id')->count('student_id');
        $todayAttendances = Attendance::today()->valid()->count();

        return [
            'total_attendances' => $totalAttendances,
            'unique_students' => $uniqueStudents,
            'today_attendances' => $todayAttendances,
            'total_students' => Student::active()->count(),
        ];
    }

    /**
     * Invalidate attendance
     *
     * @param int $attendanceId
     * @param int $modifiedBy
     * @return bool
     */
    public static function invalidate(int $attendanceId, int $modifiedBy): bool
    {
        $attendance = Attendance::find($attendanceId);

        if ($attendance) {
            $attendance->is_valid = false;
            $attendance->modified_by = $modifiedBy;
            $attendance->modified_at = now();

            return $attendance->save();
        }

        return false;
    }
}
