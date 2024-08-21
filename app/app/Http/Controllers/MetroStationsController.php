<?php

namespace App\Http\Controllers;

use App\Models\MetroStation;
use Illuminate\Http\JsonResponse;

class MetroStationsController extends Controller
{
    public function underConstruction(): JsonResponse
    {
        return response()->json(MetroStation::where('under_construction', 1)->get());
    }
}
