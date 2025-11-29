<?php

namespace App\Services;

use App\Models\QrCode;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;
use Exception;

class QrCodeService
{
    /**
     * Generate QR code for student
     *
     * @param int $studentId
     * @param string|null $sessionName
     * @return array
     */
    public static function generate(int $studentId, ?string $sessionName = null): array
    {
        try {
            $student = Student::find($studentId);

            if (!$student) {
                return [
                    'success' => false,
                    'message' => 'Student not found'
                ];
            }

            // Generate unique QR data
            $qrData = 'ATT_' . $studentId . '_' . uniqid() . '_' . time();
            $qrCodeHash = hash('sha256', $qrData);

            // Generate QR code as SVG inline (no file needed)
            $qrImageSvg = QrCodeGenerator::format('svg')
                ->size(300)
                ->margin(2)
                ->generate($qrCodeHash);

            // Also save to storage for backup
            $fileName = 'qr_' . $studentId . '_' . time() . '.svg';
            $storagePath = config('attendance.storage.qrcodes', 'qrcodes');
            $filePath = $storagePath . '/' . $fileName;

            Storage::put($filePath, $qrImageSvg);

            // Create data URL for immediate display
            $base64Svg = base64_encode($qrImageSvg);
            $dataUrl = 'data:image/svg+xml;base64,' . $base64Svg;

            // Calculate expiry time
            $expiryMinutes = (int) (\App\Models\Setting::get(
                'qr_expiry_minutes',
                config('attendance.qr_code.expiry_minutes', 30)
            ) ?? 30);
            $validUntil = now()->addMinutes($expiryMinutes);

            // Save to database
            $qrCode = QrCode::create([
                'student_id' => $studentId,
                'qr_code' => $qrCodeHash,
                'qr_file_path' => $fileName,
                'session_name' => $sessionName ?? 'Auto-' . now()->format('Y-m-d'),
                'valid_until' => $validUntil,
            ]);

            return [
                'success' => true,
                'qr_code' => $qrCodeHash,
                'qr_file' => $fileName,
                'qr_url' => $dataUrl,
                'valid_until' => $validUntil->toDateTimeString(),
                'student_name' => $student->student_name,
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to generate QR code: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Validate QR code
     *
     * @param string $qrCodeHash
     * @return array
     */
    public static function validate(string $qrCodeHash): array
    {
        $qrCode = QrCode::where('qr_code', $qrCodeHash)
            ->where('is_used', false)
            ->first();

        if (!$qrCode) {
            return [
                'success' => false,
                'message' => 'Invalid or already used QR code'
            ];
        }

        if ($qrCode->valid_until < now()) {
            return [
                'success' => false,
                'message' => 'QR code has expired'
            ];
        }

        return [
            'success' => true,
            'student_id' => $qrCode->student_id,
            'qr_code_id' => $qrCode->id,
            'session_name' => $qrCode->session_name,
        ];
    }

    /**
     * Mark QR code as used
     *
     * @param string $qrCodeHash
     * @return bool
     */
    public static function markAsUsed(string $qrCodeHash): bool
    {
        $qrCode = QrCode::where('qr_code', $qrCodeHash)->first();

        if ($qrCode) {
            return $qrCode->markAsUsed();
        }

        return false;
    }

    /**
     * Cleanup expired QR codes
     *
     * @return int Number of deleted QR codes
     */
    public static function cleanupExpired(): int
    {
        $expiredQrCodes = QrCode::expired()->get();

        foreach ($expiredQrCodes as $qrCode) {
            // Delete file from storage
            if ($qrCode->qr_file_path) {
                $filePath = config('attendance.storage.qrcodes') . '/' . $qrCode->qr_file_path;
                Storage::delete($filePath);
            }
        }

        return QrCode::expired()->delete();
    }

    /**
     * Get QR code image URL
     *
     * @param string $fileName
     * @return string
     */
    public static function getQrCodeUrl(string $fileName): string
    {
        $filePath = config('attendance.storage.qrcodes') . '/' . $fileName;
        return Storage::url($filePath);
    }
}
