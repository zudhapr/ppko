<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function heartbeat(Request $request)
    {
        $validated = $request->validate([
            'device_code' =>
                'required|exists:devices,device_code',

            'mode' =>
                'nullable|in:AUTO,DEMO',

            'current_hst' =>
                'nullable|integer',

            'ip_address' =>
                'nullable|string',

            'firmware_version' =>
                'nullable|string|max:50',
        ]);

        $device = Device::where(
            'device_code',
            $validated['device_code']
        )->firstOrFail();

        $device->update([
            'last_seen' => now(),

            'mode' =>
                $validated['mode']
                ?? $device->mode,

            'current_hst' =>
                $validated['current_hst']
                ?? $device->current_hst,

            'ip_address' =>
                $validated['ip_address']
                ?? $device->ip_address,

            'firmware_version' =>
                $validated['firmware_version']
                ?? $device->firmware_version,
        ]);

        return response()->json([
            'success' => true,

            'server_time' =>
                now()->format('Y-m-d H:i:s'),

            'device' => [
                'id' => $device->id,
                'name' => $device->name,
                'mode' => $device->mode,
            ]
        ]);
    }
}