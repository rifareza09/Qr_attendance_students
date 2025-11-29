@extends('layouts.app')

@section('title', 'Verify 2FA')

@section('content')
<div class="min-h-screen gradient-bg flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Two-Factor Authentication</h1>
                <p class="text-gray-600 mt-2">Enter the 6-digit code from your authenticator app</p>
            </div>

            @if(isset($qrCodeUrl))
            <div class="mb-6 text-center">
                <p class="text-sm text-gray-600 mb-4">Scan this QR code with Google Authenticator:</p>
                <img src="{{ $qrCodeUrl }}" alt="2FA QR Code" class="mx-auto">
            </div>
            @endif

            <form action="{{ route('admin.verify-2fa') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Authentication Code</label>
                    <input type="text" name="code" required maxlength="6" pattern="[0-9]{6}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-center text-2xl font-mono"
                        placeholder="000000">
                    @error('code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full gradient-bg text-white font-bold py-3 px-6 rounded-lg hover:opacity-90 transition">
                    Verify
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
