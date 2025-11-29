<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Services\EncryptionService;
use App\Services\NetworkValidator;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Display student registration page
     */
    public function index()
    {
        $networkInfo = NetworkValidator::getNetworkInfo();
        $ip = NetworkValidator::getClientIP();

        // Check if student already exists
        $existingStudent = Student::where('student_ip', $ip)->first();

        return view('student.index', compact('networkInfo', 'ip', 'existingStudent'));
    }

    /**
     * Register new student
     */
    public function register(Request $request)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
        ]);

        $ip = NetworkValidator::getClientIP();
        $name = $request->student_name;

        // Check if already registered
        $existing = Student::where('student_ip', $ip)->first();
        if ($existing) {
            return back()->with('error', 'Student already registered with this IP address');
        }

        // Generate credentials
        $username = EncryptionService::generateUsername('student');
        $password = EncryptionService::generatePassword(12);
        $hashedPassword = Hash::make($password);

        // Encrypt sensitive data
        $encryptedData = EncryptionService::encrypt(json_encode([
            'name' => $name,
            'ip' => $ip,
            'registered_at' => now()->toDateTimeString()
        ]));

        // Create student
        $student = Student::create([
            'student_ip' => $ip,
            'student_name' => $name,
            'username' => $username,
            'password' => $hashedPassword,
            'encrypted_data' => $encryptedData,
            'is_active' => true,
        ]);

        // Generate QR code
        $qrResult = QrCodeService::generate($student->id, 'Registration-' . now()->format('Y-m-d'));

        return view('student.registered', [
            'student' => $student,
            'plain_password' => $password,
            'qr_result' => $qrResult
        ]);
    }

    /**
     * Show student dashboard
     */
    public function dashboard($id)
    {
        $student = Student::with(['attendances' => function($query) {
            $query->orderBy('attendance_date', 'desc')->limit(50);
        }])->findOrFail($id);

        // Get statistics
        $stats = [
            'total' => $student->total_attendance,
            'this_month' => $student->attendance_this_month,
            'this_week' => $student->attendance_this_week,
        ];

        return view('student.dashboard', compact('student', 'stats'));
    }

    /**
     * Show QR code scanner page
     */
    public function showScanQr()
    {
        return view('student.scan-qr');
    }
}
