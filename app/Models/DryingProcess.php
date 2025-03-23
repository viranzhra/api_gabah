<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DryingProcess extends Model
{
    use HasFactory;

    protected $primaryKey = 'process_id';

    protected $fillable = ['dryer_id', 'user_id', 'grain_type_id', 'timestamp_mulai', 'timestamp_selesai', 'berat_gabah', 'kadar_air_target', 'kadar_air_akhir', 'durasi_rekomendasi', 'durasi_aktual', 'status'];
}
