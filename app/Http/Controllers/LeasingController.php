<?php

namespace App\Http\Controllers;

use App\Http\Resources\LeasingResource;
use App\Repositories\LeasingRepo;
use Illuminate\Http\JsonResponse;

class LeasingController extends Controller
{
    private LeasingRepo $leasingRepo;

    public function __construct(LeasingRepo $leasingRepo)
    {
        $this->leasingRepo = $leasingRepo;
    }

    public function getLeasings(): JsonResponse
    {
        $leasings = LeasingResource::collection($this->leasingRepo->list());

        return response()->json(['success' => true, 'leasings' => $leasings]);
    }
}
