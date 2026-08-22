<?php

namespace App\Http\Controllers;

use App\Models\GrowthPhase;
use Illuminate\Http\Request;

class GrowthPhaseController extends Controller
{
    public function index()
    {
        $phases = GrowthPhase::orderBy('name')
            ->get();

        return view(
            'master.phases.index',
            compact('phases')
        );
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' =>
                'required|string|max:255',

            'description' =>
                'nullable|string|max:1000',
        ]);


        GrowthPhase::create([
            'name' =>
                $validated['name'],

            'description' =>
                $validated['description']
                ?? null,

            'is_active' => true,
        ]);


        return back()->with(
            'success',
            'Fase pertumbuhan berhasil ditambahkan.'
        );
    }


    public function update(
        Request $request,
        GrowthPhase $phase
    ) {
        $validated = $request->validate([
            'name' =>
                'required|string|max:255',

            'description' =>
                'nullable|string|max:1000',
        ]);


        $phase->update([
            'name' =>
                $validated['name'],

            'description' =>
                $validated['description']
                ?? null,
        ]);


        return back()->with(
            'success',
            'Fase pertumbuhan berhasil diperbarui.'
        );
    }


    public function toggle(
        GrowthPhase $phase
    ) {
        $phase->update([
            'is_active' =>
                !$phase->is_active
        ]);


        return back()->with(
            'success',
            'Status fase berhasil diubah.'
        );
    }


    public function destroy(
        GrowthPhase $phase
    ) {
        if ($phase->schedules()->exists()) {

            return back()->with(
                'error',
                'Fase masih digunakan dalam jadwal.'
            );
        }


        $phase->delete();


        return back()->with(
            'success',
            'Fase berhasil dihapus.'
        );
    }
}