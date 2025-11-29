<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip if 2FA is disabled globally
        if (!config('attendance.enable_2fa')) {
            return $next($request);
        }

        $user = $request->user();

        // Check if user has 2FA enabled and not yet verified in this session
        if ($user && $user->is_2fa_enabled && !session('2fa_verified')) {
            return redirect()->route('admin.verify-2fa');
        }

        return $next($request);
    }
}
