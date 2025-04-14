<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dryer;

class DryerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menambahkan 10 data dryer secara otomatis menggunakan factory
        Dryer::factory(10)->create();
    }
}
