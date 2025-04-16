<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorDataController;
use App\Http\Controllers\Api\PredictionApiController;
use App\Http\Controllers\Api\GrainTypeApiController;
use App\Http\Controllers\Api\DryingProcessController;

Route::get('/drying-processes', [DryingProcessController::class, 'index']);
Route::post('/drying-processes', [DryingProcessController::class, 'store']);
Route::get('/drying-processes/{id}', [DryingProcessController::class, 'show']);
Route::put('/drying-processes/{id}', [DryingProcessController::class, 'update']);
Route::delete('/drying-processes/{id}', [DryingProcessController::class, 'destroy']);
Route::put('/drying-processes/start/{id}', [DryingProcessController::class, 'start']);
Route::put('/drying-processes/finish/{id}', [DryingProcessController::class, 'finish']);
Route::post('/drying-process/add-sensor/{processId}', [DryingProcessController::class, 'addSensorData'])->name('drying-process.add-sensor');



// Endpoint untuk mendapatkan semua jenis gabah
Route::get('/graintypes', [GrainTypeApiController::class, 'index']);

// Endpoint untuk memproses prediksi dan menyimpan hasilnya
Route::post('/prediksi', [PredictionApiController::class, 'store']);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// === Dummy route untuk testing ===
Route::get('/sensor/data', function () {
    return response()->json([
        'data' => [
            [
                'sensor_id' => 1,
                'dryer_id' => 101,
                'timestamp' => '2025-04-13T08:00:00',
                'kadar_air_gabah' => 15.2,
                'suhu_gabah' => 38.5,
                'suhu_ruangan' => 30.1,
            ],
            [
                'sensor_id' => 2,
                'dryer_id' => 101,
                'timestamp' => '2025-04-13T09:00:00',
                'kadar_air_gabah' => 14.7,
                'suhu_gabah' => 39.0,
                'suhu_ruangan' => 29.8,
            ]
        ]
    ]);
});

// (opsional, kalau IoT kamu udah kirim data ke sini)
Route::post('/sensor/store', [SensorDataController::class, 'store']);
