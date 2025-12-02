<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\NetworkValidator;
use Symfony\Component\HttpFoundation\Response;

class ValidateIP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If testing mode is enabled, skip IP validation
        if (config('attendance.testing_mode')) {
            // Generate random IP for testing to simulate different devices
            $randomIp = '192.168.' . rand(1, 254) . '.' . rand(1, 254);
            $request->merge(['client_ip' => $randomIp]);
            return $next($request);
        }

        $ip = NetworkValidator::getClientIP();

        // Validate IP format
        if (!NetworkValidator::isValidIP($ip)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid IP address'
                ], 400);
            }

            return redirect()->back()->with('error', 'Invalid IP address');
        }

        // Add IP to request for easy access
        $request->merge(['client_ip' => $ip]);

        return $next($request);
    }
}
