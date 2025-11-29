<?php

namespace App\Services;

use Exception;

class EncryptionService
{
    /**
     * Encrypt data using AES-256-CBC
     *
     * @param string $data
     * @return string
     * @throws Exception
     */
    public static function encrypt(string $data): string
    {
        $key = config('attendance.encryption_key');
        $method = config('attendance.encryption_method');

        if (empty($key)) {
            throw new Exception('Encryption key is not set');
        }

        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
        $encrypted = openssl_encrypt($data, $method, $key, 0, $iv);

        if ($encrypted === false) {
            throw new Exception('Encryption failed');
        }

        return base64_encode($encrypted . '::' . $iv);
    }

    /**
     * Decrypt data using AES-256-CBC
     *
     * @param string $data
     * @return string
     * @throws Exception
     */
    public static function decrypt(string $data): string
    {
        $key = config('attendance.encryption_key');
        $method = config('attendance.encryption_method');

        if (empty($key)) {
            throw new Exception('Encryption key is not set');
        }

        $decoded = base64_decode($data);
        $parts = explode('::', $decoded, 2);

        if (count($parts) !== 2) {
            throw new Exception('Invalid encrypted data format');
        }

        [$encryptedData, $iv] = $parts;

        $decrypted = openssl_decrypt($encryptedData, $method, $key, 0, $iv);

        if ($decrypted === false) {
            throw new Exception('Decryption failed');
        }

        return $decrypted;
    }

    /**
     * Hash password
     *
     * @param string $password
     * @return string
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Verify password against hash
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Generate random password
     *
     * @param int $length
     * @return string
     */
    public static function generatePassword(int $length = 12): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';
        $charLength = strlen($chars);

        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, $charLength - 1)];
        }

        return $password;
    }

    /**
     * Generate unique username
     *
     * @param string $prefix
     * @return string
     */
    public static function generateUsername(string $prefix = 'student'): string
    {
        return $prefix . '_' . uniqid() . '_' . substr(md5(time()), 0, 6);
    }
}
