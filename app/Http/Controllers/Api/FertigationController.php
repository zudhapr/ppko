<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Planting;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class FertigationController extends Controller
{
    public function schedule(): JsonResponse
    {
        $planting = Planting::with([
            'fertigationProfile.schedules.valve'
        ])
        ->where('is_active', true)
        ->first();

        if (!$planting) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada penanaman aktif.',
            ], 404);
        }

        $today = Carbon::today();

        $hst = $planting->planting_date->diffInDays($today);

        $schedules = $planting
            ->fertigationProfile
            ->schedules()
            ->with('valve')
            ->where('hst', $hst)
            ->where('is_active', true)
            ->get()
            ->sortBy('start_time')
            ->values();

        return response()->json([
            'success' => true,

            'server_time' => now()
                ->timezone('Asia/Jakarta')
                ->format('Y-m-d H:i:s'),

            'planting' => [
                'id' => $planting->id,
                'name' => $planting->name,
                'planting_date' => $planting->planting_date
                    ->format('Y-m-d'),
                'hst' => $hst,
            ],

            'profile' => [
                'id' => $planting->fertigationProfile->id,
                'name' => $planting->fertigationProfile->name,
            ],

            'schedules' => $schedules->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'valve_id' => $schedule->valve_id,
                    'valve_name' => $schedule->valve->name,
                    'gpio' => $schedule->valve->gpio,
                    'start_time' => $schedule->start_time,
                    'duration_seconds' => $schedule->duration_seconds,
                ];
            }),
        ]);
    }
}