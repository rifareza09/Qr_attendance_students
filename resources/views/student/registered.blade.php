@extends('layouts.app')

@section('title', 'Registration Successful')

@section('content')
<div class="min-h-screen gradient-bg py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-6">
                <div class="text-green-500 text-6xl mb-4">✓</div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Registration Successful!</h1>
                <p class="text-gray-600">Welcome, {{ $student->student_name }}</p>
            </div>

            <!-- Credentials Warning -->
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
                <p class="text-yellow-800 font-semibold">⚠️ IMPORTANT: Save your credentials!</p>
                <p class="text-yellow-700 text-sm">This is the only time you will see your password. Please save it securely.</p>
            </div>

            <!-- Credentials -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Your Login Credentials</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-600 text-sm">Username:</p>
                        <p class="font-mono font-bold text-lg">{{ $student->username }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Password:</p>
                        <p class="font-mono font-bold text-lg">{{ $plain_password }}</p>
                    </div>
                </div>
            </div>

            <!-- QR Code -->
            @if($qr_result['success'])
            <div class="text-center mb-6">
                <h2 class="text-xl font-semibold mb-4">Your QR Code</h2>
                <div class="bg-gray-100 p-6 rounded-lg inline-block">
                    <img src="{{ $qr_result['qr_url'] }}" alt="QR Code" class="w-64 h-64">
                </div>
                <p class="text-sm text-gray-600 mt-2">Valid until: {{ $qr_result['valid_until'] }}</p>
            </div>
            @endif

            <!-- Actions -->
            <div class="space-y-3">
                <a href="{{ route('student.dashboard', $student->id) }}"
                   class="block w-full text-center gradient-bg text-white font-bold py-3 px-6 rounded-lg hover:opacity-90 transition">
                    Go to Dashboard
                </a>
                <a href="{{ route('student.index') }}"
                   class="block w-full text-center border-2 border-gray-300 text-gray-700 font-bold py-3 px-6 rounded-lg hover:bg-gray-50 transition">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
