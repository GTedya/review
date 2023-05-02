<?php

namespace App\Http\Controllers;

use App\Repositories\GeoRepo;
use Illuminate\Http\JsonResponse;

class GeoController extends Controller
{
    public GeoRepo $geoRepo;

    public function __construct(GeoRepo $geoRepo)
    {
        $this->geoRepo = $geoRepo;
    }

    public function pagination(): JsonResponse
    {
        return response()->json(['success' => true, 'geos' => $this->geoRepo->pagination()]);
    }
}
