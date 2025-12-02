<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Services\AttendanceService;
use App\Services\NetworkValidator;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Mark attendance
     */
    public function mark(Request $request)
    {
        // Use client_ip from middleware (supports testing mode with random IPs)
        $ip = $request->client_ip ?? NetworkValidator::getClientIP();

        // Mark attendance by IP
        $result = AttendanceService::markByIP($ip);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Mark attendance with QR code
     */
    public function markWithQR(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        // Use client_ip from middleware (supports testing mode with random IPs)
        $ip = $request->client_ip ?? NetworkValidator::getClientIP();
        $qrCode = $request->qr_code;

        $result = AttendanceService::markWithQR($qrCode, $ip);

        if ($request->expectsJson()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Scan QR code and mark attendance
     */
    public function scanQr(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        // Use client_ip from middleware (supports testing mode with random IPs)
        $ip = $request->client_ip ?? NetworkValidator::getClientIP();
        $qrCode = $request->qr_code;

        $result = AttendanceService::markWithQR($qrCode, $ip);

        if ($result['success']) {
            return redirect()->route('student.index')->with('success', $result['message']);
        }

        return redirect()->route('student.scan-qr')->with('error', $result['message']);
    }

    /**
     * Get attendance history for a student
     */
    public function history($studentId)
    {
        $attendances = Attendance::where('student_id', $studentId)
            ->with('student')
            ->orderBy('attendance_date', 'desc')
            ->paginate(config('attendance.pagination.attendance'));

        return response()->json($attendances);
    }
}
