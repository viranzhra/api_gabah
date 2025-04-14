<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Dryer;
use App\Models\DryingProcess;
use App\Models\GrainType;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    public function run(): void
    {
        // Jalankan seeder untuk data dasar
        $this->call([
            GrainTypeSeeder::class,
            DryerSeeder::class,
            SensorDataSeeder::class,
        ]);

        $user = User::create([
            'id' => 9,
            'name' => 'User 9',
            'email' => 'user9@example.com',
            'password' => bcrypt('password'),
        ]);

        // Ambil grain_type dan dryer yang sudah tersedia
        $dryer = Dryer::inRandomOrder()->first();
        $grainType = GrainType::inRandomOrder()->first();

        // Pastikan keduanya tersedia
        if ($dryer && $grainType) {
            DryingProcess::factory(10)->create([
                'user_id' => $user->id,
                'dryer_id' => $dryer->id,
                'grain_type_id' => $grainType->id,
            ]);
        } else {
            echo "❌ Pastikan data dryer dan grain type tersedia sebelum membuat drying process.\n";
        }
    }
}
