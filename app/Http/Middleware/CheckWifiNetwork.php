<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\NetworkValidator;
use Symfony\Component\HttpFoundation\Response;

class CheckWifiNetwork
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip if WiFi check is disabled
        if (!config('attendance.enable_wifi_check')) {
            return $next($request);
        }

        // Validate WiFi network
        if (!NetworkValidator::isValidWiFi()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be connected to the valid Wi-Fi network'
                ], 403);
            }

            return redirect()->back()->with('error', 'You must be connected to the valid Wi-Fi network');
        }

        return $next($request);
    }
}
