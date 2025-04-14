<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SensorData;
use Carbon\Carbon;

class SensorDataSeeder extends Seeder
{
    public function run(): void
    {
        SensorData::insert([
            [
                'dryer_id' => 1,
                'timestamp' => Carbon::now()->subHour(),
                'kadar_air_gabah' => 15.2,
                'suhu_gabah' => 38.5,
                'suhu_ruangan' => 30.1,
            ],
            [
                'dryer_id' => 2,
                'timestamp' => Carbon::now(),
                'kadar_air_gabah' => 14.7,
                'suhu_gabah' => 39.0,
                'suhu_ruangan' => 29.8,
            ]
        ]);
    }
}