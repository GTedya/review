<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderManagersResource;
use App\Repositories\ManagerRepo;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    private ManagerRepo $managerRepo;


    public function __construct(ManagerRepo $managerRepo)
    {
        $this->managerRepo = $managerRepo;
    }

    public function orders(): JsonResponse
    {
        $orders = $this->managerRepo->getOrders(Auth::id());

        return response()->json(['success' => true, 'orders' => OrderManagersResource::collection($orders)->resource]);
    }

    public function getOrder(int $orderId): JsonResponse
    {
        $order = $this->managerRepo->getById(Auth::id(), $orderId);

        return response()->json(
            [
                'success' => true,
                'order' => OrderManagersResource::make($order),
            ]
        );
    }
}
