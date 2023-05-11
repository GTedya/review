<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClaimRequest;
use App\Http\Resources\ClaimResource;
use App\Repositories\ClaimRepo;
use Illuminate\Http\JsonResponse;

class ClaimController extends Controller
{
    private ClaimRepo $claimRepo;

    public function __construct(ClaimRepo $claimRepo)
    {
        $this->claimRepo = $claimRepo;
    }

    public function putClaim(ClaimRequest $claimRequest): JsonResponse
    {
        $claim = $this->claimRepo->create($claimRequest->validated());
        return response()->json(['success' => true, 'claim' => ClaimResource::make($claim)]);
    }
}
