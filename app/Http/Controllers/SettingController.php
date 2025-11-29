<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'valid_wifi_ssid' => 'required|string|max:100',
            'qr_expiry_minutes' => 'required|integer|min:5|max:1440',
        ]);

        Setting::set('valid_wifi_ssid', $request->valid_wifi_ssid, 'Valid WiFi network SSID');
        Setting::set('qr_expiry_minutes', $request->qr_expiry_minutes, 'QR code expiry time in minutes');

        activity()
            ->causedBy(Auth::user())
            ->log('Settings updated');

        return back()->with('success', 'Settings updated successfully');
    }
}
