<?php

namespace App\Console;

use App\Console\Commands\AddSensorData;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        AddSensorData::class,  // Daftarkan command yang baru dibuat
    ];

    protected function schedule(Schedule $schedule)
    {
        // Menjadwalkan command untuk berjalan setiap menit
        $schedule->command('sensor:add')->everyMinute();
    }
}
