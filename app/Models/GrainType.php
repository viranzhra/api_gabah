<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrainType extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = ['nama_jenis', 'deskripsi'];

    public function dryingProcesses()
    {
        return $this->hasMany(DryingProcess::class);
    }
}
