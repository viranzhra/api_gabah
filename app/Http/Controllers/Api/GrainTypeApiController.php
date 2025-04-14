<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GrainType;

class GrainTypeApiController extends Controller
{
    public function index()
    {
        $grainTypes = GrainType::all();

        return response()->json([
            'status' => true,
            'data' => $grainTypes
        ]);
    }
}