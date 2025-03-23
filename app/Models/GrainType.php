<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrainType extends Model
{
    use HasFactory;

    protected $primaryKey = 'grain_type_id';

    protected $fillable = ['nama_jenis', 'deskripsi'];
}
