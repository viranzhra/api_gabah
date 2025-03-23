<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingData extends Model
{
    use HasFactory;

    protected $primaryKey = 'training_id';

    protected $fillable = ['process_id', 'kadar_air_awal', 'suhu_gabah', 'suhu_ruangan', 'berat_gabah', 'durasi_pengeringan', 'kadar_air_akhir'];
}
