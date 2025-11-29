@extends('layouts.app')

@section('title', 'Scan QR Code')

@section('content')
<div class="min-h-screen gradient-bg py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-white mb-2">Scan QR Code</h1>
            <p class="text-white text-opacity-90">Scan the QR code displayed by your instructor</p>
        </div>

        <!-- QR Scanner Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-4">
            <div class="mb-4">
                <div id="qr-reader" class="w-full"></div>
            </div>

            <div id="qr-reader-results" class="hidden">
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                    <p class="text-sm text-blue-700">QR Code detected! Processing...</p>
                </div>
            </div>

            <!-- Manual Input Option -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-600 mb-3">Can't scan? Enter QR code manually:</p>
                <form action="{{ route('attendance.scan') }}" method="POST">
                    @csrf
                    <div class="flex gap-2">
                        <input type="text" name="qr_code" required
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Paste QR code here">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-4">
            <p class="text-white text-sm">
                <strong>Instructions:</strong><br>
                1. Allow camera access when prompted<br>
                2. Point your camera at the QR code<br>
                3. Wait for automatic detection<br>
                4. Your attendance will be marked automatically
            </p>
        </div>

        <!-- Back Button -->
        <div class="mt-6 text-center">
            <a href="{{ route('student.index') }}" class="text-white hover:underline">
                ← Back to Home
            </a>
        </div>
    </div>
</div>

<!-- QR Code Scanner Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
    let html5QrCode;

    function onScanSuccess(decodedText, decodedResult) {
        // Show processing message
        document.getElementById('qr-reader-results').classList.remove('hidden');

        // Stop scanning
        html5QrCode.stop().then(() => {
            // Submit the form automatically
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('attendance.scan') }}';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const qrCodeInput = document.createElement('input');
            qrCodeInput.type = 'hidden';
            qrCodeInput.name = 'qr_code';
            qrCodeInput.value = decodedText;

            form.appendChild(csrfToken);
            form.appendChild(qrCodeInput);
            document.body.appendChild(form);
            form.submit();
        }).catch((err) => {
            console.error('Error stopping scanner:', err);
        });
    }

    function onScanFailure(error) {
        // Handle scan failure silently
    }

    // Initialize QR Code scanner
    html5QrCode = new Html5Qrcode("qr-reader");

    const config = {
        fps: 10,
        qrbox: { width: 250, height: 250 }
    };

    html5QrCode.start(
        { facingMode: "environment" }, // Use back camera
        config,
        onScanSuccess,
        onScanFailure
    ).catch((err) => {
        // If camera not available, show manual input only
        console.error('Camera error:', err);
        document.getElementById('qr-reader').innerHTML =
            '<div class="bg-yellow-50 border-l-4 border-yellow-500 p-4">' +
            '<p class="text-sm text-yellow-700">Camera not available. Please use manual input below.</p>' +
            '</div>';
    });
</script>
@endsection
