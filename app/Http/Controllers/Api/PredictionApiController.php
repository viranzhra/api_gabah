<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DryingProcess;

class PredictionApiController extends Controller
{
    public function predict(Request $request)
    {
        $request->validate([
            'berat_gabah' => 'required|numeric',
            'suhu_gabah' => 'required|numeric',
            'suhu_ruangan' => 'required|numeric',
            'kadar_air_awal' => 'required|numeric',
            'kadar_air_akhir' => 'required|numeric',
            'grain_type_id' => 'required|integer',
        ]);

        // Dummy prediksi (contoh: semakin banyak berat, semakin lama)
        $durasi = intval(($request->berat_gabah * 0.5) + ($request->suhu_gabah + $request->suhu_ruangan) * 0.2 + ($request->kadar_air_awal - $request->kadar_air_akhir) * 10);

        return response()->json([
            'durasi_prediksi' => $durasi
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'berat_gabah' => 'required|numeric',
            'suhu_gabah' => 'required|numeric',
            'suhu_ruangan' => 'required|numeric',
            'kadar_air_awal' => 'required|numeric',
            'kadar_air_akhir' => 'required|numeric',
            'grain_type_id' => 'required|exists:grain_types,grain_type_id',
            'user_id' => 'required|exists:users,id',
            'dryer_id' => 'required|integer'
        ]);

        // Hitung durasi prediksi (misalnya dummy)
        $durasi_prediksi = rand(30, 90); // ganti dengan model ML aslinya

        $proses = DryingProcess::create([
            'dryer_id' => $request->dryer_id,
            'user_id' => $request->user_id,
            'grain_type_id' => $request->grain_type_id,
            'timestamp_mulai' => now(),
            'berat_gabah' => $request->berat_gabah,
            'kadar_air_target' => $request->kadar_air_akhir,
            'durasi_rekomendasi' => 0,
            'durasi_prediksi' => $durasi_prediksi,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Berhasil memproses prediksi.',
            'durasi_prediksi' => $durasi_prediksi,
            'data' => $proses
        ]);
    }
}
