@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<div class="min-h-screen gradient-bg flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Admin Login</h1>
                <p class="text-gray-600 mt-2">Student Attendance System</p>
            </div>

            <form action="{{ route('admin.login') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Username</label>
                    <input type="text" name="username" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter username">
                    @error('username')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter password">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full gradient-bg text-white font-bold py-3 px-6 rounded-lg hover:opacity-90 transition">
                    Login
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('student.index') }}" class="text-sm text-blue-600 hover:underline">
                    ← Back to Student Portal
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
