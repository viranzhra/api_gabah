<?php

namespace Database\Factories;

use App\Models\Dryer;
use Illuminate\Database\Eloquent\Factories\Factory;

class DryerFactory extends Factory
{
    protected $model = Dryer::class;

    public function definition(): array
    {
        return [
            'nama_alat' => $this->faker->word(),
            'lokasi' => $this->faker->address(),
            'kapasitas' => $this->faker->randomFloat(2, 1, 10),  // Random float between 1 and 10
        ];
    }
}
