<?php

namespace App\Http\Controllers;

use App\Http\Resources\GeoResource;
use App\Repositories\GeoRepo;
use Illuminate\Http\JsonResponse;

class GeoController extends Controller
{
    public GeoRepo $geoRepo;

    public function __construct(GeoRepo $geoRepo)
    {
        $this->geoRepo = $geoRepo;
    }

    public function list(): JsonResponse
    {
        return response()->json(['success' => true, 'geos' => GeoResource::collection($this->geoRepo->list())]);
    }
}
