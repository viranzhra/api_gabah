<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    use HasFactory;

    protected $primaryKey = 'sensor_id';

    protected $fillable = ['dryer_id', 'timestamp', 'kadar_air_gabah', 'suhu_gabah', 'suhu_ruangan'];
}
