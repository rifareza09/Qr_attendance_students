@extends('layouts.app')

@section('title', 'Student Attendance')

@section('content')
<div class="min-h-screen gradient-bg py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-white mb-2">Student Attendance System</h1>
            <p class="text-white text-opacity-90">Mark your attendance with QR Code</p>
        </div>

        <!-- Network Info Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Network Information</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600">Your IP Address:</p>
                    <p class="font-semibold">{{ $ip }}</p>
                </div>
                <div>
                    <p class="text-gray-600">WiFi SSID:</p>
                    <p class="font-semibold">{{ $networkInfo['wifi_ssid'] ?? 'Not Connected' }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-600">WiFi Status:</p>
                    @if($networkInfo['is_valid_wifi'])
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">✓ Valid WiFi</span>
                    @else
                        <span class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">✗ Invalid WiFi</span>
                    @endif
                </div>
            </div>
        </div>

        @if($existingStudent)
            <!-- Existing Student Card -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Welcome Back, {{ $existingStudent->student_name }}!</h2>

                @if(is_marked_today($existingStudent->id))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4">
                        <p class="text-green-800 font-semibold">✓ You have already marked attendance today!</p>
                    </div>
                @else
                    <div class="space-y-3">
                        <form action="{{ route('attendance.mark') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full gradient-bg text-white font-bold py-3 px-6 rounded-lg hover:opacity-90 transition">
                                Mark Attendance Now (IP-Based)
                            </button>
                        </form>

                        <a href="{{ route('student.scan-qr') }}" class="block w-full bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition text-center">
                            📱 Scan QR Code
                        </a>
                    </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('student.dashboard', $existingStudent->id) }}" class="block text-center text-blue-600 hover:underline">
                        View My Dashboard
                    </a>
                </div>
            </div>
        @else
            <!-- Registration Form -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">New Student Registration</h2>
                <p class="text-gray-600 mb-6">Enter your name to register and get your credentials</p>

                <form action="{{ route('student.register') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Full Name</label>
                        <input type="text" name="student_name" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter your full name">
                        @error('student_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full gradient-bg text-white font-bold py-3 px-6 rounded-lg hover:opacity-90 transition">
                        Register Now
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
