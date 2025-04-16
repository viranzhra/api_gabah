<?php

namespace App\Console\Commands;

use App\Models\DryingProcess;
use App\Models\SensorData;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AddSensorData extends Command
{
    protected $signature = 'sensor:add';
    protected $description = 'Menambahkan data sensor setiap detik selama proses pengeringan masih berlangsung.';

    public function handle()
    {
        $this->info("Menjalankan penambahan data sensor setiap 1 detik...");

        while (true) {
            // Ambil proses pengeringan yang masih berlangsung
            $process = DryingProcess::where('status', 'ongoing')->first();

            if ($process) {
                $startTime = Carbon::parse($process->timestamp_mulai);
                $elapsedTime = $startTime->diffInMinutes(Carbon::now());

                if ($elapsedTime < $process->durasi_rekomendasi) {
                    // Tambahkan data sensor
                    $this->generateSensorData($process->dryer_id);
                    $this->info("Data sensor ditambahkan pada " . now()->toDateTimeString());
                } else {
                    // Proses selesai, update status
                    $process->status = 'completed';
                    $process->timestamp_selesai = Carbon::now();
                    $process->save();

                    $this->info("Durasi selesai. Proses pengeringan ditandai sebagai 'completed'.");
                    break; // Keluar dari loop
                }
            } else {
                $this->info("Tidak ada proses pengeringan yang sedang berlangsung.");
                break; // Keluar dari loop
            }

            sleep(1); // Tunggu 1 detik sebelum insert berikutnya
        }
    }

    protected function generateSensorData($dryerId)
    {
        SensorData::create([
            'dryer_id' => $dryerId,
            'timestamp' => now(),
            'kadar_air_gabah' => rand(1200, 2000) / 100, // 12.00% - 20.00%
            'suhu_gabah' => rand(3000, 4000) / 100,       // 30.00°C - 40.00°C
            'suhu_ruangan' => rand(2500, 3500) / 100      // 25.00°C - 35.00°C
        ]);
    }
}
