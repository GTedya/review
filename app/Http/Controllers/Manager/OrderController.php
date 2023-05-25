<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Repositories\ManagerRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public ManagerRepo $managerRepo;

    public function __construct(ManagerRepo $managerRepo)
    {
        $this->managerRepo = $managerRepo;
    }

    public function orders(): JsonResponse
    {
        $orders = $this->managerRepo->getOrders(Auth::id());

        return response()->json(['success' => true, 'orders' => OrderResource::collection($orders)->resource]);
    }
}
