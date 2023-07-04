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
use Illuminate\Validation\ValidationException;

class RentController extends Controller
{
    private RentService $rentService;
    private RentRepo $rentRepo;

    public function __construct(RentService $rentService, RentRepo $rentRepo)
    {
        $this->rentService = $rentService;
        $this->rentRepo = $rentRepo;
    }

    /**
     * @throws ValidationException
     */
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
        $history = $this->rentRepo->history($userId, $perPage);

        return response()->json(['success' => true, 'rents' => RentResource::collection($history)->resource]);
    }

    public function list(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page');
        $geos = $request->input('geo');
        $vehTypes = $request->input('veh_types');
        $with_nds = $request->input('with_nds');
        $types = $request->input('types');
        $rents = $this->rentRepo->pagination($perPage, $geos, $with_nds, $types, $vehTypes);

        return response()->json(['success' => true, 'rents' => RentResource::collection($rents)->resource]);
    }

    /**
     * @throws ValidationException
     */
    public function extend(int $rentId): JsonResponse
    {
        $userId = Auth::id();

        $this->rentService->extend($userId, $rentId);

        return response()->json(['success' => true]);
    }

    public function single(string $slug): JsonResponse
    {
        $rent = $this->rentService->getRent($slug);
        $active = $rent->isActive;
        if (!$active) {
            return response()->json(['success' => true, 'active' => $active]);
        }
        return response()->json(['success' => true, 'rent' => RentResource::make($rent)]);
    }
}
