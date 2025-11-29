@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="gradient-bg text-white py-6 px-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">Admin Dashboard</h1>
                <p class="text-white text-opacity-90">Student Attendance Management System</p>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg transition">
                    Logout
                </button>
            </form>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-8 px-4">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total Students</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $stats['total_students'] }}</p>
                    </div>
                    <div class="text-blue-500 text-4xl">👥</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Today's Attendance</p>
                        <p class="text-3xl font-bold text-green-600">{{ $stats['today_attendances'] }}</p>
                    </div>
                    <div class="text-green-500 text-4xl">✓</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total Attendances</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $stats['total_attendances'] }}</p>
                    </div>
                    <div class="text-purple-500 text-4xl">📊</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Active Students</p>
                        <p class="text-3xl font-bold text-orange-600">{{ $stats['total_students'] }}</p>
                    </div>
                    <div class="text-orange-500 text-4xl">🎓</div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <a href="?tab=attendance"
                       class="py-4 px-6 text-center border-b-2 font-medium text-sm {{ $tab === 'attendance' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Attendance
                    </a>
                    <a href="?tab=qrcodes"
                       class="py-4 px-6 text-center border-b-2 font-medium text-sm {{ $tab === 'qrcodes' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        QR Codes
                    </a>
                    <a href="?tab=students"
                       class="py-4 px-6 text-center border-b-2 font-medium text-sm {{ $tab === 'students' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Students
                    </a>
                    <a href="?tab=settings"
                       class="py-4 px-6 text-center border-b-2 font-medium text-sm {{ $tab === 'settings' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Settings
                    </a>
                    <a href="?tab=logs"
                       class="py-4 px-6 text-center border-b-2 font-medium text-sm {{ $tab === 'logs' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Activity Logs
                    </a>
                </nav>
            </div>

            <div class="p-6">
                @if($tab === 'attendance')
                    <!-- Attendance Tab -->
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Today's Attendance</h2>
                        <a href="{{ route('admin.export') }}?format=csv" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Export CSV
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">WiFi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($data['attendances'] ?? [] as $attendance)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $attendance->id }}</td>
                                    <td class="px-6 py-4">{{ $attendance->student->student_name }}</td>
                                    <td class="px-6 py-4 font-mono text-sm">{{ $attendance->student_ip }}</td>
                                    <td class="px-6 py-4">{{ $attendance->attendance_time }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $attendance->wifi_ssid ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        @if($attendance->is_valid)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Valid</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Invalid</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($attendance->is_valid)
                                        <form action="{{ route('admin.attendance.invalidate', $attendance->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Invalidate</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No attendance records today</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(isset($data['attendances']))
                        <div class="mt-4">
                            {{ $data['attendances']->links() }}
                        </div>
                    @endif

                @elseif($tab === 'qrcodes')
                    <!-- QR Codes Tab -->
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-4">Generate QR Code for Attendance</h2>
                        
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                            <p class="text-sm text-blue-700">
                                <strong>Cara Menggunakan:</strong><br>
                                1. Pilih mahasiswa yang ingin di-generate QR code<br>
                                2. Pilih session (Morning/Afternoon/Evening)<br>
                                3. Klik "Generate QR Code"<br>
                                4. QR Code akan muncul dan bisa di-download<br>
                                5. Mahasiswa scan QR code untuk absen
                            </p>
                        </div>

                        <form action="{{ route('admin.qrcode.generate') }}" method="POST" class="bg-white p-6 rounded-lg shadow">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Select Student</label>
                                    <select name="student_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- Select Student --</option>
                                        @foreach(\App\Models\Student::where('is_active', true)->get() as $student)
                                            <option value="{{ $student->id }}">{{ $student->student_name }} ({{ $student->student_ip }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Session Name</label>
                                    <select name="session_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="Morning">Morning</option>
                                        <option value="Afternoon">Afternoon</option>
                                        <option value="Evening">Evening</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="mt-4 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                                Generate QR Code
                            </button>
                        </form>

                        @if(session('qr_generated'))
                            <div class="mt-6 bg-white p-6 rounded-lg shadow text-center">
                                <h3 class="text-lg font-semibold mb-4">QR Code Generated Successfully!</h3>
                                <div class="inline-block p-4 bg-white border-4 border-gray-200 rounded-lg">
                                    <img src="{{ session('qr_image_url') }}" alt="QR Code" class="mx-auto" style="width: 300px; height: 300px;">
                                </div>
                                <p class="mt-4 text-sm text-gray-600">Student: <strong>{{ session('student_name') }}</strong></p>
                                <p class="text-sm text-gray-600">Session: <strong>{{ session('session_name') }}</strong></p>
                                <p class="text-sm text-gray-600">Expires in: <strong>30 minutes</strong></p>
                                <a href="{{ session('qr_image_url') }}" download class="mt-4 inline-block px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                    Download QR Code
                                </a>
                            </div>
                        @endif
                    </div>

                @elseif($tab === 'students')
                    <!-- Students Tab -->
                    <h2 class="text-xl font-semibold mb-4">Registered Students</h2>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Attendance</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($data['students'] ?? [] as $student)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $student->id }}</td>
                                    <td class="px-6 py-4 font-semibold">{{ $student->student_name }}</td>
                                    <td class="px-6 py-4 font-mono text-sm">{{ $student->username }}</td>
                                    <td class="px-6 py-4 font-mono text-sm">{{ $student->student_ip }}</td>
                                    <td class="px-6 py-4">{{ $student->attendances_count }}</td>
                                    <td class="px-6 py-4">
                                        @if($student->is_active)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm space-x-2">
                                        <a href="{{ route('admin.students.credentials', $student->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                        <a href="{{ route('admin.students.edit', $student->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <form action="{{ route('admin.students.toggle', $student->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-orange-600 hover:text-orange-900">
                                                {{ $student->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No students registered yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(isset($data['students']))
                        <div class="mt-4">
                            {{ $data['students']->appends(['tab' => 'students'])->links() }}
                        </div>
                    @endif

                @elseif($tab === 'settings')
                    <!-- Settings Tab -->
                    <h2 class="text-xl font-semibold mb-6">System Settings</h2>

                    <form action="{{ route('admin.settings.update') }}" method="POST" class="max-w-2xl">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">Valid WiFi SSID</label>
                            <input type="text" name="valid_wifi_ssid"
                                   value="{{ $data['settings']['valid_wifi_ssid'] ?? config('attendance.default_wifi') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-sm text-gray-600 mt-1">The WiFi network SSID that students must connect to</p>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">QR Code Expiry (Minutes)</label>
                            <input type="number" name="qr_expiry_minutes" min="5" max="1440"
                                   value="{{ $data['settings']['qr_expiry_minutes'] ?? config('attendance.qr_code.expiry_minutes') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-sm text-gray-600 mt-1">How long QR codes remain valid (5-1440 minutes)</p>
                        </div>

                        <button type="submit" class="gradient-bg text-white font-bold py-3 px-6 rounded-lg hover:opacity-90 transition">
                            Save Settings
                        </button>
                    </form>

                @elseif($tab === 'logs')
                    <!-- Activity Logs Tab -->
                    <h2 class="text-xl font-semibold mb-4">Activity Logs</h2>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($data['logs'] ?? [] as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm">{{ $log->created_at->format('d M Y H:i:s') }}</td>
                                    <td class="px-6 py-4">{{ $log->causer->name ?? 'System' }}</td>
                                    <td class="px-6 py-4 font-mono text-sm">{{ $log->log_name }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $log->description }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No activity logs yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(isset($data['logs']))
                        <div class="mt-4">
                            {{ $data['logs']->appends(['tab' => 'logs'])->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
