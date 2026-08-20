<?php

use Illuminate\Support\Facades\Route;

use App\Models\Planting;
use App\Models\FertigationSchedule;
use App\Models\Valve;

Route::get('/', function () {
    $planting = Planting::with('fertigationProfile')
        ->where('is_active', true)
        ->first();

    $hst = null;
    $todaySchedules = collect();

    if ($planting) {
        $hst = $planting->planting_date
            ->diffInDays(now()->startOfDay());

        $todaySchedules = FertigationSchedule::with('valve')
            ->where('fertigation_profile_id', $planting->fertigation_profile_id)
            ->where('hst', $hst)
            ->where('is_active', true)
            ->orderBy('start_time')
            ->get();
    }

    $valveCount = Valve::where('is_active', true)->count();

    return view('welcome', compact(
        'planting',
        'hst',
        'todaySchedules',
        'valveCount'
    ));
});

use App\Http\Controllers\FertigationScheduleController;

Route::get('/jadwal', [
    FertigationScheduleController::class,
    'index'
])->name('jadwal.index');

Route::post('/jadwal', [
    FertigationScheduleController::class,
    'store'
])->name('jadwal.store');

Route::put('/jadwal/{jadwal}', [
    FertigationScheduleController::class,
    'update'
])->name('jadwal.update');

Route::delete('/jadwal/{jadwal}', [
    FertigationScheduleController::class,
    'destroy'
])->name('jadwal.destroy');

Route::patch('/jadwal/{jadwal}/toggle', [
    FertigationScheduleController::class,
    'toggle'
])->name('jadwal.toggle');

use App\Http\Controllers\FertigationProfileController;
use App\Http\Controllers\ValveController;


// PROFIL FERTIGASI

Route::get('/master/profil-fertigasi', [
    FertigationProfileController::class,
    'index'
])->name('profiles.index');

Route::post('/master/profil-fertigasi', [
    FertigationProfileController::class,
    'store'
])->name('profiles.store');

Route::put('/master/profil-fertigasi/{profile}', [
    FertigationProfileController::class,
    'update'
])->name('profiles.update');

Route::patch('/master/profil-fertigasi/{profile}/toggle', [
    FertigationProfileController::class,
    'toggle'
])->name('profiles.toggle');

Route::delete('/master/profil-fertigasi/{profile}', [
    FertigationProfileController::class,
    'destroy'
])->name('profiles.destroy');


// VALVE

Route::get('/master/valve', [
    ValveController::class,
    'index'
])->name('valves.index');

Route::post('/master/valve', [
    ValveController::class,
    'store'
])->name('valves.store');

Route::put('/master/valve/{valve}', [
    ValveController::class,
    'update'
])->name('valves.update');

Route::patch('/master/valve/{valve}/toggle', [
    ValveController::class,
    'toggle'
])->name('valves.toggle');

Route::delete('/master/valve/{valve}', [
    ValveController::class,
    'destroy'
])->name('valves.destroy');

use App\Http\Controllers\DemoController;

Route::get('/demo', [
    DemoController::class,
    'index'
])->name('demo.index');

Route::post('/demo/command', [
    DemoController::class,
    'command'
])->name('demo.command');