<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceCommand;
use App\Models\Valve;
use Illuminate\Http\Request;

class DemoController extends Controller
{
    public function index()
    {
        $device = Device::where('is_active', true)
            ->first();

        $valves = Valve::where('is_active', true)
            ->orderBy('name')
            ->get();

        $commands = DeviceCommand::with([
                'device',
                'valve'
            ])
            ->latest()
            ->limit(20)
            ->get();

        return view('demo.index', compact(
            'device',
            'valves',
            'commands'
        ));
    }


    public function command(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'required|exists:devices,id',
            'valve_id' => 'required|exists:valves,id',
            'command' => 'required|in:TEST_OPEN,CLOSE',
            'duration_seconds' => 'nullable|integer|min:1|max:60',
        ]);

        $device = Device::findOrFail(
            $validated['device_id']
        );

        /*
         * Untuk TEST_OPEN wajib memiliki durasi.
         */
        if (
            $validated['command'] === 'TEST_OPEN' &&
            empty($validated['duration_seconds'])
        ) {
            return back()->with(
                'error',
                'Durasi test valve harus diisi.'
            );
        }

        /*
         * Batalkan command pending lama.
         */
        DeviceCommand::where(
                'device_id',
                $device->id
            )
            ->where('status', 'pending')
            ->update([
                'status' => 'expired'
            ]);

        DeviceCommand::create([
            'device_id' => $device->id,

            'valve_id' =>
                $validated['valve_id'],

            'command' =>
                $validated['command'],

            'duration_seconds' =>
                $validated['command'] === 'TEST_OPEN'
                    ? $validated['duration_seconds']
                    : null,

            'status' => 'pending',

            /*
             * Command demo hanya berlaku 30 detik.
             */
            'expires_at' =>
                now()->addSeconds(30),
        ]);

        return back()->with(
            'success',
            'Perintah demo dikirim.'
        );
    }
}