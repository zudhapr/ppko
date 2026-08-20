<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DeviceCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeviceCommandController extends Controller
{
    public function claim(Request $request)
    {
        $validated = $request->validate([
            'device_code' =>
                'required|exists:devices,device_code'
        ]);

        $device = Device::where(
            'device_code',
            $validated['device_code']
        )->firstOrFail();


        /*
         * Tandai perintah lama sebagai expired.
         */
        DeviceCommand::where(
                'device_id',
                $device->id
            )
            ->where('status', 'pending')
            ->where('expires_at', '<', now())
            ->update([
                'status' => 'expired'
            ]);


        $command = DB::transaction(function () use ($device) {

            $command = DeviceCommand::with('valve')
                ->where('device_id', $device->id)
                ->where('status', 'pending')
                ->where(function ($query) {

                    $query
                        ->whereNull('expires_at')
                        ->orWhere(
                            'expires_at',
                            '>',
                            now()
                        );

                })
                ->orderBy('id')
                ->lockForUpdate()
                ->first();


            if (!$command) {
                return null;
            }


            /*
             * Begitu diberikan ke ESP,
             * status menjadi running.
             */
            $command->update([
                'status' => 'running',
                'started_at' => now()
            ]);

            return $command;
        });


        if (!$command) {

            return response()->json([
                'success' => true,
                'command' => null
            ]);

        }


        return response()->json([
            'success' => true,

            'command' => [
                'id' => $command->id,

                'type' =>
                    $command->command,

                'valve_id' =>
                    $command->valve_id,

                'valve_name' =>
                    $command->valve->name,

                'gpio' =>
                    $command->valve->gpio,

                'duration_seconds' =>
                    $command->duration_seconds,
            ]
        ]);
    }


    public function complete(
        Request $request,
        DeviceCommand $command
    ) {
        $validated = $request->validate([
            'device_code' =>
                'required|exists:devices,device_code',

            'success' =>
                'required|boolean',

            'message' =>
                'nullable|string|max:500'
        ]);


        $device = Device::where(
            'device_code',
            $validated['device_code']
        )->firstOrFail();


        /*
         * Pastikan command memang milik ESP tersebut.
         */
        if ($command->device_id !== $device->id) {

            return response()->json([
                'success' => false,
                'message' => 'Command tidak valid.'
            ], 403);

        }


        $command->update([
            'status' =>
                $validated['success']
                    ? 'completed'
                    : 'failed',

            'completed_at' =>
                now(),

            'message' =>
                $validated['message'] ?? null,
        ]);


        return response()->json([
            'success' => true
        ]);
    }
}