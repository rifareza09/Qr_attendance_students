@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<div class="min-h-screen bg-gray-100 py-12 px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Student Dashboard</h1>
            <p class="text-gray-600">Welcome, {{ $student->student_name }}</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total Attendance</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $stats['total'] }}</p>
                    </div>
                    <div class="text-blue-500 text-4xl">📊</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">This Month</p>
                        <p class="text-3xl font-bold text-green-600">{{ $stats['this_month'] }}</p>
                    </div>
                    <div class="text-green-500 text-4xl">📅</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">This Week</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $stats['this_week'] }}</p>
                    </div>
                    <div class="text-purple-500 text-4xl">⏰</div>
                </div>
            </div>
        </div>

        <!-- Attendance History -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold">Attendance History</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Session</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">WiFi SSID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($student->attendances as $attendance)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $attendance->attendance_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $attendance->attendance_time }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $attendance->session_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $attendance->wifi_ssid ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($attendance->is_valid)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Present</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Invalid</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No attendance records found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('student.index') }}" class="inline-block px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                ← Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
