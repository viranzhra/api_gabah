<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorData;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SensorDataController extends Controller {
    public function store(Request $request) {
        $data = $request->validate([
            'dryer_id' => 'nullable|integer',
            'timestamp' => 'required|date',
            'kadar_air_gabah' => 'required|numeric',
            'suhu_gabah' => 'required|numeric',
            'suhu_ruangan' => 'required|numeric',
        ]);

        $sensorData = SensorData::create($data);

        // Kirim ke ML server
        try {
            $response = Http::post('http://localhost:5000/api/predict', [
                'kadar_air_gabah' => $sensorData->kadar_air_gabah,
                'suhu_gabah' => $sensorData->suhu_gabah,
                'suhu_ruangan' => $sensorData->suhu_ruangan,
            ]);

            Log::info('ML Response:', ['data' => $response->json()]);
        } catch (\Exception $e) {
            Log::error('ML Request Failed', ['error' => $e->getMessage()]);
        }

        return response()->json(['message' => 'Data berhasil disimpan dan dikirim ke ML']);
    }

    public function index() {
        $data = SensorData::latest()->paginate(10);
        return response()->json($data);
    }
}