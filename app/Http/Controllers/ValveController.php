<?php

namespace App\Http\Controllers;

use App\Models\Valve;
use Illuminate\Http\Request;

class ValveController extends Controller
{
    public function index()
    {
        $valves = Valve::orderBy('name')->get();

        return view('master.valves.index', compact('valves'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gpio' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        Valve::create([
            'name' => $validated['name'],
            'gpio' => $validated['gpio'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_active' => true,
        ]);

        return back()->with('success', 'Valve berhasil ditambahkan.');
    }

    public function update(
        Request $request,
        Valve $valve
    ) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gpio' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $valve->update($validated);

        return back()->with('success', 'Valve berhasil diperbarui.');
    }

    public function toggle(Valve $valve)
    {
        $valve->update([
            'is_active' => !$valve->is_active
        ]);

        return back()->with('success', 'Status valve diubah.');
    }

    public function destroy(Valve $valve)
    {
        if ($valve->schedules()->exists()) {
            return back()->with(
                'error',
                'Valve masih digunakan dalam jadwal.'
            );
        }

        $valve->delete();

        return back()->with('success', 'Valve berhasil dihapus.');
    }
}