<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DryingProcess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DryingProcessController extends Controller
{
    public function index()
    {
        return response()->json(DryingProcess::all());
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

    public function start($id)
    {
        $drying = DryingProcess::find($id);

        if (!$drying) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        $drying->status = 'proses'; // atau 'in_progress' sesuai konvensi
        $drying->timestamp_mulai = now(); // bisa diperbarui lagi kalau mau
        $drying->save();

        return response()->json(['message' => 'Proses pengeringan dimulai.']);
    }

    public function finish($id)
    {
        $drying = DryingProcess::find($id);

        if (!$drying) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        if ($drying->status !== 'proses' && $drying->status !== 'ongoing') {
            return response()->json(['message' => 'Proses tidak sedang berjalan.'], 400);
        }

        $drying->status = 'selesai'; // atau 'completed'
        $drying->timestamp_selesai = now();
        $drying->durasi_aktual = now()->diffInMinutes($drying->timestamp_mulai);
        $drying->kadar_air_akhir = 13.5; // Default / dummy value, bisa diganti

        $drying->save();

        return response()->json(['message' => 'Proses pengeringan selesai.']);
    }
}
