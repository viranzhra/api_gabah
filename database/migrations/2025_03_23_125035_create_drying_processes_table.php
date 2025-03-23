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
        Schema::create('drying_processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dryer_id')->constrained('dryers')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('grain_type_id')->constrained('grain_types')->onDelete('cascade');
            $table->timestamp('timestamp_mulai');
            $table->timestamp('timestamp_selesai')->nullable();
            $table->float('berat_gabah');
            $table->float('kadar_air_target');
            $table->float('kadar_air_akhir')->nullable();
            $table->integer('durasi_rekomendasi');
            $table->integer('durasi_aktual')->nullable();
            $table->enum('status', ['pending', 'ongoing', 'completed']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drying_processes');
    }
};
