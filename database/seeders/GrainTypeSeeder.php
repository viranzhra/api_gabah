<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GrainType;

class GrainTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menambahkan beberapa jenis grain untuk keperluan seeding
        GrainType::create(['id' => 1, 'nama_jenis' => 'Rice', 'deskripsi' => 'Jenis gabah biasa digunakan untuk padi.']);
        GrainType::create(['id' => 2, 'nama_jenis' => 'Corn', 'deskripsi' => 'Jenis gabah untuk jagung.']);
        GrainType::create(['id' => 3, 'nama_jenis' => 'Wheat', 'deskripsi' => 'Jenis gabah untuk gandum.']);
        GrainType::create(['id' => 4, 'nama_jenis' => 'Barley', 'deskripsi' => 'Jenis gabah untuk barley.']);
    }
}
