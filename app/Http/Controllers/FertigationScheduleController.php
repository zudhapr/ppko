<?php

namespace App\Http\Controllers;

use App\Models\FertigationProfile;
use App\Models\FertigationSchedule;
use App\Models\Planting;
use App\Models\Valve;
use App\Models\GrowthPhase;
use Illuminate\Http\Request;

class FertigationScheduleController extends Controller
{
    public function index(Request $request)
    {
        $profiles = FertigationProfile::where(
            'is_active',
            true
        )
        ->orderBy('name')
        ->get();


        $valves = Valve::where(
            'is_active',
            true
        )
        ->orderBy('name')
        ->get();


        $phases = GrowthPhase::where(
            'is_active',
            true
        )
        ->orderBy('name')
        ->get();


        $profileId = $request->get(
            'profile_id',
            $profiles->first()?->id
        );


        $profile = FertigationProfile::find(
            $profileId
        );


        $schedules = collect();


        if ($profile) {

            $schedules = $profile
                ->schedules()

                ->with([
                    'valve',
                    'growthPhase'
                ])

                ->orderBy('hst_start')
                ->orderBy('hst_end')
                ->orderBy('start_time')

                ->get();
        }


        $planting = Planting::with(
            'fertigationProfile'
        )
        ->where('is_active', true)
        ->first();


        $hst = null;


        if ($planting) {

            $hst = (int) $planting
                ->planting_date
                ->startOfDay()
                ->diffInDays(
                    now()->startOfDay(),
                    false
                );
        }


        return view(
            'fertigation.schedules.index',
            compact(
                'profiles',
                'valves',
                'phases',
                'profile',
                'schedules',
                'planting',
                'hst'
            )
        );
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'fertigation_profile_id' =>
                'required|exists:fertigation_profiles,id',

            'valve_id' =>
                'required|exists:valves,id',

            'growth_phase_id' =>
                'required|exists:growth_phases,id',

            'hst_start' =>
                'required|integer|min:0|max:365',

            'hst_end' =>
                'required|integer|min:0|max:365|gte:hst_start',

            'start_time' =>
                'required|date_format:H:i',

            'duration_minutes' =>
                'required|integer|min:1|max:1440',
        ]);


        FertigationSchedule::create([
            'fertigation_profile_id' =>
                $validated['fertigation_profile_id'],

            'valve_id' =>
                $validated['valve_id'],

            'growth_phase_id' =>
                $validated['growth_phase_id'],

            'hst_start' =>
                $validated['hst_start'],

            'hst_end' =>
                $validated['hst_end'],

            'start_time' =>
                $validated['start_time'],

            'duration_seconds' =>
                $validated['duration_minutes'] * 60,

            'is_active' => true,
        ]);


        return redirect()
            ->route('jadwal.index', [
                'profile_id' =>
                    $validated[
                        'fertigation_profile_id'
                    ]
            ])
            ->with(
                'success',
                'Jadwal berhasil ditambahkan.'
            );
    }


   public function update(
    Request $request,
    FertigationSchedule $jadwal
    ) {

        $validated = $request->validate([
            'fertigation_profile_id' =>
                'required|exists:fertigation_profiles,id',

            'valve_id' =>
                'required|exists:valves,id',

            'growth_phase_id' =>
                'required|exists:growth_phases,id',

            'hst_start' =>
                'required|integer|min:0|max:365',

            'hst_end' =>
                'required|integer|min:0|max:365|gte:hst_start',

            'start_time' =>
                'required|date_format:H:i',

            'duration_minutes' =>
                'required|integer|min:1|max:1440',
        ]);


        $jadwal->update([
            'fertigation_profile_id' =>
                $validated['fertigation_profile_id'],

            'valve_id' =>
                $validated['valve_id'],

            'growth_phase_id' =>
                $validated['growth_phase_id'],

            'hst_start' =>
                $validated['hst_start'],

            'hst_end' =>
                $validated['hst_end'],

            'start_time' =>
                $validated['start_time'],

            'duration_seconds' =>
                $validated['duration_minutes'] * 60,
        ]);


        return redirect()
            ->route('jadwal.index', [
                'profile_id' =>
                    $validated[
                        'fertigation_profile_id'
                    ]
            ])
            ->with(
                'success',
                'Jadwal berhasil diperbarui.'
            );
    }


    public function toggle(
        FertigationSchedule $jadwal
    ) {
        $jadwal->update([
            'is_active' =>
                !$jadwal->is_active
        ]);

        return back()->with(
            'success',
            'Status jadwal berhasil diubah.'
        );
    }


    public function destroy(
        FertigationSchedule $jadwal
    ) {
        $profileId =
            $jadwal->fertigation_profile_id;

        $jadwal->delete();

        return redirect()
            ->route('jadwal.index', [
                'profile_id' => $profileId
            ])
            ->with(
                'success',
                'Jadwal berhasil dihapus.'
            );
    }
}