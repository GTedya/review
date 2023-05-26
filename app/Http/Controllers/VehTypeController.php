<?php

namespace App\Http\Controllers;

use App\Http\Resources\VehTypeResource;
use App\Repositories\VehTypeRepo;
use Illuminate\Http\JsonResponse;

class VehTypeController extends Controller

{
    public VehTypeRepo $typeRepo;

    public function __construct(VehTypeRepo $typeRepo)
    {
        $this->typeRepo = $typeRepo;
    }


    public function list(): JsonResponse
    {
        $types = $this->typeRepo->list();

        return response()->json(['success' => true, 'types' => VehTypeResource::collection($types)]);
    }
}
