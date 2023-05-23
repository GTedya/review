<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\PartnerResource;
use App\Repositories\PartnerRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    private PartnerRepo $partnerRepo;

    public function __construct(PartnerRepo $partnerRepo)
    {
        $this->partnerRepo = $partnerRepo;
    }

    public function getPartner(Request $request): JsonResponse
    {
        $limit = $request->input('limit');

        return response()->json(
            ['success' => true, 'partners' => PartnerResource::collection($this->partnerRepo->list($limit))]
        );
    }
}
