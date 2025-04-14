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
        Schema::table('drying_processes', function (Blueprint $table) {
            $table->integer('durasi_prediksi')->nullable()->after('durasi_rekomendasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drying_processes', function (Blueprint $table) {
            $table->dropColumn('durasi_prediksi');
        });
    }
};
