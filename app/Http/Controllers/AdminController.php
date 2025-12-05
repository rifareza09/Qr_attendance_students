<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Setting;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;
use Spatie\Activitylog\Models\Activity;

class AdminController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('admin.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            /** @var User $user */
            $user = Auth::user();

            // Update last login
            $user->last_login = now();
            $user->save();

            // Check if 2FA is enabled
            if ($user->is_2fa_enabled && config('attendance.enable_2fa')) {
                session(['2fa_pending' => true]);
                return redirect()->route('admin.verify-2fa');
            }

            session(['2fa_verified' => true]);

            activity()
                ->causedBy($user)
                ->log('Admin logged in');

            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Invalid credentials');
    }

    /**
     * Show 2FA verification form
     */
    public function show2FA()
    {
        if (!session('2fa_pending')) {
            return redirect()->route('admin.login');
        }

        /** @var User $user */
        $user = Auth::user();

        // Generate QR code for first-time setup
        $qrCodeUrl = null;
        if (empty($user->two_fa_secret)) {
            $google2fa = new Google2FA();
            $secret = $google2fa->generateSecretKey();
            $user->two_fa_secret = $secret;
            $user->save();

            $qrCodeUrl = $google2fa->getQRCodeUrl(
                config('app.name'),
                $user->email,
                $secret
            );
        }

        return view('admin.verify-2fa', compact('qrCodeUrl'));
    }

    /**
     * Verify 2FA code
     */
    public function verify2FA(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey($user->two_fa_secret, $request->code);

        if ($valid) {
            session(['2fa_verified' => true]);
            session()->forget('2fa_pending');

            activity()
                ->causedBy($user)
                ->log('2FA verification successful');

            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Invalid 2FA code');
    }

    /**
     * Show admin dashboard
     */
    public function dashboard(Request $request)
    {
        $tab = $request->get('tab', 'attendance');

        // Get statistics
        $stats = AttendanceService::getStatistics();

        // Get data based on tab
        $data = [];

        switch ($tab) {
            case 'attendance':
                $data['attendances'] = Attendance::with('student')
                    ->today()
                    ->orderBy('marked_at', 'desc')
                    ->paginate(config('attendance.pagination.attendance'));
                break;

            case 'students':
                $data['students'] = Student::withCount('attendances')
                    ->orderBy('created_at', 'desc')
                    ->paginate(config('attendance.pagination.students'));
                break;

            case 'settings':
                $data['settings'] = Setting::getAllSettings();
                break;

            case 'logs':
                $data['logs'] = Activity::orderBy('created_at', 'desc')
                    ->paginate(config('attendance.pagination.logs'));
                break;
        }

        return view('admin.dashboard', compact('stats', 'tab', 'data'));
    }

    /**
     * Deactivate student
     */
    public function deactivateStudent($id)
    {
        $student = Student::findOrFail($id);
        $student->is_active = !$student->is_active;
        $student->save();

        activity()
            ->causedBy(Auth::user())
            ->performedOn($student)
            ->log('Student ' . ($student->is_active ? 'activated' : 'deactivated'));

        return back()->with('success', 'Student status updated');
    }

    /**
     * View student credentials
     */
    public function viewCredentials($id)
    {
        $student = Student::findOrFail($id);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($student)
            ->log('Viewed student credentials');

        return view('admin.view-credentials', compact('student'));
    }

    /**
     * Invalidate attendance
     */
    public function invalidateAttendance($id)
    {
        $success = AttendanceService::invalidate($id, Auth::id());

        if ($success) {
            return back()->with('success', 'Attendance invalidated');
        }

        return back()->with('error', 'Failed to invalidate attendance');
    }

    /**
     * Export data
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = Attendance::with('student')->valid();

        if ($dateFrom && $dateTo) {
            $query->whereBetween('attendance_date', [$dateFrom, $dateTo]);
        }

        $attendances = $query->get();

        if ($format === 'json') {
            return response()->json($attendances);
        }

        // CSV Export
        $filename = 'attendance-export-' . now()->format('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($attendances) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, ['ID', 'Student Name', 'IP Address', 'Date', 'Time', 'Session', 'WiFi SSID', 'Valid']);

            // Data rows
            foreach ($attendances as $attendance) {
                fputcsv($file, [
                    $attendance->id,
                    $attendance->student->student_name,
                    $attendance->student_ip,
                    $attendance->attendance_date,
                    $attendance->attendance_time,
                    $attendance->session_name,
                    $attendance->wifi_ssid,
                    $attendance->is_valid ? 'Yes' : 'No',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate QR Code for student
     */
    public function generateQrCode(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'session_name' => 'required|string',
        ]);

        $student = Student::findOrFail($request->student_id);
        
        $result = \App\Services\QrCodeService::generate(
            $request->student_id,
            $request->session_name
        );

        if ($result['success']) {
            activity()
                ->causedBy(Auth::user())
                ->performedOn($student)
                ->log('Generated QR code for student');

            return redirect()
                ->route('admin.dashboard', ['tab' => 'qrcodes'])
                ->with([
                    'qr_generated' => true,
                    'qr_image_url' => $result['qr_url'],
                    'student_name' => $student->student_name,
                    'session_name' => $request->session_name,
                ]);
        }

        return back()->with('error', $result['message'] ?? 'Failed to generate QR code');
    }

    /**
     * View student details
     */
    public function viewStudent($id)
    {
        $student = Student::findOrFail($id);
        $attendances = Attendance::where('student_id', $id)
            ->orderBy('attendance_date', 'desc')
            ->orderBy('attendance_time', 'desc')
            ->get();

        activity()
            ->causedBy(Auth::user())
            ->performedOn($student)
            ->log('Viewed student details');

        return view('admin.students.view', compact('student', 'attendances'));
    }

    /**
     * Show edit student form
     */
    public function editStudent($id)
    {
        $student = Student::findOrFail($id);

        return view('admin.students.edit', compact('student'));
    }

    /**
     * Update student information
     */
    public function updateStudent(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'student_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:students,username,' . $id,
            'student_ip' => 'required|ip|unique:students,student_ip,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'is_active' => 'required|boolean',
        ]);

        $data = [
            'student_name' => $request->student_name,
            'username' => $request->username,
            'student_ip' => $request->student_ip,
            'is_active' => $request->is_active,
        ];

        // Update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $student->update($data);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($student)
            ->withProperties([
                'old' => $student->getOriginal(),
                'new' => $student->toArray()
            ])
            ->log('Updated student information');

        return redirect()
            ->route('admin.students.view', $student->id)
            ->with('success', 'Student information updated successfully!');
    }

    /**
     * Delete student
     */
    public function deleteStudent($id)
    {
        $student = Student::findOrFail($id);
        $studentName = $student->student_name;

        activity()
            ->causedBy(Auth::user())
            ->performedOn($student)
            ->withProperties([
                'student' => $student->toArray()
            ])
            ->log('Deleted student');

        // Delete student (cascade will delete related records)
        $student->delete();

        return redirect()
            ->route('admin.dashboard', ['tab' => 'students'])
            ->with('success', "Student '{$studentName}' has been deleted successfully!");
    }

    /**
     * Logout
     */
    public function logout()
    {
        activity()
            ->causedBy(Auth::user())
            ->log('Admin logged out');

        Auth::logout();
        session()->flush();

        return redirect()->route('admin.login');
    }
}
