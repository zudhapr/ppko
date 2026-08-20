<?php

namespace App\Http\Controllers;

use App\Models\FertigationProfile;
use App\Models\FertigationSchedule;
use App\Models\Planting;
use App\Models\Valve;
use Illuminate\Http\Request;

class FertigationScheduleController extends Controller
{
    public function index(Request $request)
    {
        $profiles = FertigationProfile::where('is_active', true)
            ->orderBy('name')
            ->get();

        $valves = Valve::where('is_active', true)
            ->orderBy('name')
            ->get();

        $profileId = $request->get(
            'profile_id',
            $profiles->first()?->id
        );

        $profile = FertigationProfile::find($profileId);

        $schedules = collect();

        if ($profile) {
            $schedules = $profile->schedules()
                ->with('valve')
                ->orderBy('hst')
                ->orderBy('start_time')
                ->get()
                ->groupBy('hst');
        }

        $planting = Planting::with('fertigationProfile')
            ->where('is_active', true)
            ->first();

        $hst = null;

        if ($planting) {
            $hst = $planting->planting_date
                ->diffInDays(now()->startOfDay());
        }

        return view('fertigation.schedules.index', compact(
            'profiles',
            'valves',
            'profile',
            'schedules',
            'planting',
            'hst'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fertigation_profile_id' => 'required|exists:fertigation_profiles,id',
            'valve_id' => 'required|exists:valves,id',
            'hst' => 'required|integer|min:0|max:365',
            'start_time' => 'required|date_format:H:i',
            'duration_minutes' => 'required|integer|min:1|max:1440',
        ]);

        FertigationSchedule::create([
            'fertigation_profile_id' => $validated['fertigation_profile_id'],
            'valve_id' => $validated['valve_id'],
            'hst' => $validated['hst'],
            'start_time' => $validated['start_time'],
            'duration_seconds' => $validated['duration_minutes'] * 60,
            'is_active' => true,
        ]);

        return redirect()
            ->route('jadwal.index', [
                'profile_id' => $validated['fertigation_profile_id']
            ])
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function update(Request $request, FertigationSchedule $jadwal)
    {
        $validated = $request->validate([
            'fertigation_profile_id' => 'required|exists:fertigation_profiles,id',
            'valve_id' => 'required|exists:valves,id',
            'hst' => 'required|integer|min:0|max:365',
            'start_time' => 'required|date_format:H:i',
            'duration_minutes' => 'required|integer|min:1|max:1440',
        ]);

        $jadwal->update([
            'fertigation_profile_id' => $validated['fertigation_profile_id'],
            'valve_id' => $validated['valve_id'],
            'hst' => $validated['hst'],
            'start_time' => $validated['start_time'],
            'duration_seconds' => $validated['duration_minutes'] * 60,
        ]);

        return redirect()
            ->route('jadwal.index', [
                'profile_id' => $validated['fertigation_profile_id']
            ])
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(FertigationSchedule $jadwal)
    {
        $profileId = $jadwal->fertigation_profile_id;

        $jadwal->delete();

        return redirect()
            ->route('jadwal.index', [
                'profile_id' => $profileId
            ])
            ->with('success', 'Jadwal berhasil dihapus.');
    }

    public function toggle(FertigationSchedule $jadwal)
    {
        $jadwal->update([
            'is_active' => !$jadwal->is_active
        ]);

        return back()->with(
            'success',
            'Status jadwal berhasil diubah.'
        );
    }
}