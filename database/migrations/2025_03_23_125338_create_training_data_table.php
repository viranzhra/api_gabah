<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('training_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('process_id')->constrained('drying_processes')->onDelete('cascade');
            $table->float('kadar_air_awal');
            $table->float('suhu_gabah');
            $table->float('suhu_ruangan');
            $table->float('berat_gabah');
            $table->integer('durasi_pengeringan');
            $table->float('kadar_air_akhir');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_data');
    }
};
