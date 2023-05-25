<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\RentRequest;
use App\Http\Resources\RentResource;
use App\Models\User;
use App\Repositories\RentRepo;
use App\Services\RentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentController extends Controller
{
    private RentService $rentService;
    private RentRepo $rentRepo;

    public function __construct(RentService $rentService, RentRepo $rentRepo)
    {
        $this->rentService = $rentService;
        $this->rentRepo = $rentRepo;
    }

    public function create(RentRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $rent = $this->rentService->create($user, $request->validated());
        $rent = $rent->fresh('rentVehicles');

        return response()->json(['success' => true, 'rent' => RentResource::make($rent)]);
    }

    public function history(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $perPage = $request->input('per_page');

        return response()->json(['success' => true, 'ads' => $this->rentRepo->history($userId, $perPage)]);
    }

    public function list(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page');
        $geos = $request->input('geo');
        $types = $request->input('types');
        $rents = $this->rentRepo->pagination($perPage, $geos, $types);


        return response()->json(['success' => true, 'ads' => RentResource::collection($rents)]);
    }
}
