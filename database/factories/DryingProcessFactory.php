<?php

namespace Database\Factories;

use App\Models\DryingProcess;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Dryer;

class DryingProcessFactory extends Factory
{
    /**
     * Model yang digunakan.
     *
     * @var string
     */
    protected $model = DryingProcess::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $timestampMulai = Carbon::now()->subHours($this->faker->numberBetween(1, 24));
        $status = $this->faker->randomElement(['pending', 'ongoing', 'completed']);
        $timestampSelesai = $status === 'completed' ? $timestampMulai->copy()->addMinutes($this->faker->numberBetween(30, 600)) : null;
        $kadarAirAkhir = $status === 'completed' ? $this->faker->randomFloat(1, 12, 15) : null;
        $durasiAktual = $status === 'completed' ? $this->faker->numberBetween(150, 400) : null;

        return [
            'dryer_id' => Dryer::factory(),  // Menggunakan factory untuk Dryer
            'user_id' => User::factory(),    // Menggunakan factory untuk User
            'grain_type_id' => $this->faker->numberBetween(1, 5),
            'timestamp_mulai' => $this->faker->dateTime(),
            'timestamp_selesai' => $this->faker->dateTime(),
            'berat_gabah' => $this->faker->randomFloat(2, 1000, 2000),
            'kadar_air_target' => $this->faker->numberBetween(10, 20),
            'kadar_air_akhir' => $this->faker->numberBetween(10, 20),
            'durasi_rekomendasi' => $this->faker->numberBetween(200, 300),
            'durasi_aktual' => $this->faker->numberBetween(200, 300),
            'status' => $this->faker->randomElement(['ongoing', 'completed']),
        ];
    }
}
