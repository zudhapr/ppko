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
        $planting = Planting::with(
            'fertigationProfile'
        )
        ->where('is_active', true)
        ->first();

        if (!$planting) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Tidak ada penanaman aktif.'
            ], 404);
        }


        if (!$planting->fertigationProfile) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Penanaman belum memiliki profil fertigasi.'
            ], 404);
        }


        $today = Carbon::today();

        $hst = (int) $planting->planting_date
            ->startOfDay()
            ->diffInDays(
                $today,
                false
            );


        /*
         * Kalau tanggal tanam masih di masa depan
         */
        if ($hst < 0) {
            return response()->json([
                'success' => true,

                'server_time' => now()
                    ->timezone('Asia/Jakarta')
                    ->format('Y-m-d H:i:s'),

                'planting' => [
                    'id' => $planting->id,
                    'name' => $planting->name,

                    'planting_date' =>
                        $planting->planting_date
                            ->format('Y-m-d'),

                    'hst' => $hst,
                ],

                'profile' => [
                    'id' =>
                        $planting
                            ->fertigationProfile
                            ->id,

                    'name' =>
                        $planting
                            ->fertigationProfile
                            ->name,
                ],

                'schedules' => []
            ]);
        }


        /*
         * Jadwal aktif apabila:
         *
         * hst_start <= HST sekarang
         * DAN
         * hst_end >= HST sekarang
         */

        $schedules = $planting
            ->fertigationProfile
            ->schedules()
            ->with('valve')
            ->where(
                'hst_start',
                '<=',
                $hst
            )
            ->where(
                'hst_end',
                '>=',
                $hst
            )
            ->where('is_active', true)
            ->orderBy('start_time')
            ->get();


        return response()->json([
            'success' => true,

            'server_time' => now()
                ->timezone('Asia/Jakarta')
                ->format('Y-m-d H:i:s'),

            'planting' => [
                'id' => $planting->id,

                'name' =>
                    $planting->name,

                'planting_date' =>
                    $planting
                        ->planting_date
                        ->format('Y-m-d'),

                'hst' => $hst,
            ],

            'profile' => [
                'id' =>
                    $planting
                        ->fertigationProfile
                        ->id,

                'name' =>
                    $planting
                        ->fertigationProfile
                        ->name,
            ],

            'schedules' =>
                $schedules->map(
                    function ($schedule) {

                        return [
                            'id' =>
                                $schedule->id,

                            'valve_id' =>
                                $schedule->valve_id,

                            'valve_name' =>
                                $schedule
                                    ->valve
                                    ->name,

                            'gpio' =>
                                $schedule
                                    ->valve
                                    ->gpio,

                            'start_time' =>
                                $schedule
                                    ->start_time,

                            'duration_seconds' =>
                                $schedule
                                    ->duration_seconds,
                        ];
                    }
                ),
        ]);
    }
}