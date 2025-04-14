<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dryer extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = ['nama_alat', 'lokasi', 'kapasitas'];

    public function sensorData()
    {
        return $this->hasMany(SensorData::class, 'dryer_id');
    }

    public function dryingProcesses()
    {
        return $this->hasMany(DryingProcess::class, 'dryer_id');
    }
}

