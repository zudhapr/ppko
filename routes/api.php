<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\DeviceCommandController;
use App\Http\Controllers\Api\FertigationController;


// JADWAL

Route::get('/fertigation/schedule', [
    FertigationController::class,
    'schedule'
]);


// HEARTBEAT ESP

Route::post('/device/heartbeat', [
    DeviceController::class,
    'heartbeat'
]);


// AMBIL PERINTAH DEMO

Route::post('/device/commands/claim', [
    DeviceCommandController::class,
    'claim'
]);


// LAPOR SELESAI

Route::post('/device/commands/{command}/complete', [
    DeviceCommandController::class,
    'complete'
]);