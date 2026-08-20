<?php

namespace App\Http\Controllers;

use App\Models\FertigationProfile;
use Illuminate\Http\Request;

class FertigationProfileController extends Controller
{
    public function index()
    {
        $profiles = FertigationProfile::orderBy('name')->get();

        return view('master.profiles.index', compact('profiles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        FertigationProfile::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => true,
        ]);

        return back()->with('success', 'Profil berhasil ditambahkan.');
    }

    public function update(
        Request $request,
        FertigationProfile $profile
    ) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $profile->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function toggle(FertigationProfile $profile)
    {
        $profile->update([
            'is_active' => !$profile->is_active
        ]);

        return back()->with('success', 'Status profil diubah.');
    }

    public function destroy(FertigationProfile $profile)
    {
        if ($profile->schedules()->exists()) {
            return back()->with(
                'error',
                'Profil masih digunakan oleh jadwal.'
            );
        }

        if ($profile->plantings()->exists()) {
            return back()->with(
                'error',
                'Profil masih digunakan oleh penanaman.'
            );
        }

        $profile->delete();

        return back()->with('success', 'Profil berhasil dihapus.');
    }
}