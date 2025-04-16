<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DryingProcess;
use App\Models\SensorData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DryingProcessController extends Controller
{
    public function index()
    {
        $data = DryingProcess::with('grainType')->get();
    
        return response()->json($data);
    }    
 
    public function show($id)
    {
        $process = DryingProcess::find($id);
        if (!$process) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($process);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dryer_id' => 'nullable|integer',
            'user_id' => 'required|integer',
            'grain_type_id' => 'required|integer',
            'timestamp_mulai' => 'required|date',
            'berat_gabah' => 'required|numeric',
            'kadar_air_target' => 'required|numeric',
            'kadar_air_akhir' => 'nullable|numeric',
            'durasi_rekomendasi' => 'required|numeric',
            'durasi_aktual' => 'nullable|numeric',
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $process = DryingProcess::create($request->all());
        return response()->json($process, 201);
    }

    // public function update(Request $request, $id)
    // {
    //     $process = DryingProcess::find($id);
    //     if (!$process) {
    //         return response()->json(['message' => 'Data tidak ditemukan'], 404);
    //     }

    //     $process->update($request->all());
    //     return response()->json($process);
    // }

    public function update(Request $request, $id)
    {
        $process = DryingProcess::find($id);

        if (!$process) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        // Validasi dinamis berdasarkan status
        $rules = [
            'status' => 'required|string|in:proses,selesai,ongoing,completed',
            'timestamp_mulai' => 'nullable|date',
            'timestamp_selesai' => 'nullable|date',
            'kadar_air_akhir' => 'nullable|numeric',
            'durasi_aktual' => 'nullable|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update nilai sesuai input yang diberikan
        if ($request->has('status')) {
            $process->status = $request->status;
        }

        if ($request->has('timestamp_mulai')) {
            $process->timestamp_mulai = $request->timestamp_mulai;
        }

        if ($request->has('timestamp_selesai')) {
            $process->timestamp_selesai = $request->timestamp_selesai;
            if ($process->timestamp_mulai && $request->timestamp_selesai) {
                $process->durasi_aktual = now()->parse($request->timestamp_selesai)->diffInMinutes($process->timestamp_mulai);
            }
        }

        if ($request->has('kadar_air_akhir')) {
            $process->kadar_air_akhir = $request->kadar_air_akhir;
        }

        $process->save();

        return response()->json(['message' => 'Proses berhasil diperbarui.']);
    }


    public function destroy($id)
    {
        $process = DryingProcess::find($id);
        if (!$process) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $process->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }

    // public function start($id)
    // {
    //     $drying = DryingProcess::find($id);

    //     if (!$drying) {
    //         return response()->json(['message' => 'Data tidak ditemukan.'], 404);
    //     }

    //     $drying->status = 'proses'; // atau 'in_progress' sesuai konvensi
    //     $drying->timestamp_mulai = Carbon::now('Asia/Jakarta');  // bisa diperbarui lagi kalau mau
    //     $drying->save();

    //     return response()->json(['message' => 'Proses pengeringan dimulai.']);
    // }

    public function start($id)
    {
        $drying = DryingProcess::find($id);

        if (!$drying) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        $drying->status = 'proses';  // Set status pengeringan ke "proses"
        $drying->timestamp_mulai = Carbon::now('Asia/Jakarta');  // Waktu mulai
        $drying->save();

        // Proses untuk menambahkan data sensor setiap detik (dummy data untuk sekarang)
        $this->generateSensorData($drying->id);  // Panggil metode untuk simulasikan data sensor

        return response()->json(['message' => 'Proses pengeringan dimulai.']);
    }

    // Fungsi untuk menambahkan data sensor setiap detik
    public function addSensorData($processId)
    {
        // Cari proses pengeringan yang sesuai
        $process = DryingProcess::where('status', 'ongoing')->first();

        if (!$process || $process->status !== 'ongoing') {
            return response()->json(['message' => 'Proses pengeringan tidak ditemukan atau belum dimulai.'], 404);
        }

        // Cek apakah durasi rekomendasi sudah tercapai
        $startTime = Carbon::parse($process->timestamp_mulai);
        $elapsedTime = $startTime->diffInMinutes(Carbon::now()); // Waktu berlalu dalam menit

        if ($elapsedTime < $process->durasi_rekomendasi) {
            // Generate data sensor random
            $kadarAirGabah = rand(12, 20) / 100;  // Kadar air antara 12% sampai 20%
            $suhuGabah = rand(30, 40);  // Suhu gabah antara 30°C sampai 40°C
            $suhuRuangan = rand(25, 35);  // Suhu ruangan antara 25°C sampai 35°C

            // Menambahkan data sensor dengan nilai random
            SensorData::create([
                'dryer_id' => $process->dryer_id,
                'timestamp' => Carbon::now(),
                'kadar_air_gabah' => $kadarAirGabah * 100,  // Kadar air dalam persen
                'suhu_gabah' => $suhuGabah,
                'suhu_ruangan' => $suhuRuangan
            ]);

            return response()->json(['message' => 'Data sensor ditambahkan.']);
        }

        // Jika durasi sudah tercapai, update status menjadi completed
        $process->status = 'completed';
        $process->timestamp_selesai = Carbon::now();
        $process->save();

        return response()->json(['message' => 'Proses pengeringan selesai.']);
    }

    public function finish($id)
    {
        $drying = DryingProcess::find($id);

        if (!$drying) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        // Check if the drying process is in progress
        if ($drying->status !== 'proses' && $drying->status !== 'ongoing') {
            return response()->json(['message' => 'Proses tidak sedang berjalan.'], 400);
        }

        // Check if the drying process has reached the recommended duration
        $currentDuration = now()->diffInMinutes($drying->timestamp_mulai);
        if ($currentDuration >= $drying->durasi_rekomendasi) {
            // If the drying duration has been reached, complete the process
            $drying->status = 'completed';
            $drying->timestamp_selesai = now();
            $drying->durasi_aktual = $currentDuration;
            $drying->kadar_air_akhir = 13.5; // Set the final moisture content, can be dynamic if needed
            $drying->save();

            // Send notification to the operator
            return response()->json([
                'message' => 'Proses pengeringan selesai. Gabah sudah kering.',
                'alert' => 'Gabah sudah kering, proses selesai.',
            ]);
        }

        return response()->json(['message' => 'Proses pengeringan belum mencapai durasi yang disarankan.'], 400);
    }
}
