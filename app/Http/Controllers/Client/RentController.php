<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\RentRequest;
use App\Http\Resources\RentResource;
use App\Models\User;
use App\Services\RentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RentController extends Controller
{
    private RentService $rentService;

    public function __construct(RentService $rentService)
    {
        $this->rentService = $rentService;
    }

    public function create(RentRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $rent = $this->rentService->create($user, $request->validated());
        $rent = $rent->fresh('rentVehicles');

        return response()->json(['success' => true, 'rent' => RentResource::make($rent)]);
    }
}
