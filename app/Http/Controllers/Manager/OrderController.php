<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderHistoryResource;
use App\Http\Resources\OrderManagersResource;
use App\Repositories\ManagerRepo;
use App\Repositories\OrderRepo;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public ManagerRepo $managerRepo;
    private OrderRepo $orderRepo;
    private OrderService $orderService;

    public function __construct(ManagerRepo $managerRepo, OrderService $orderService, OrderRepo $orderRepo)
    {
        $this->managerRepo = $managerRepo;
        $this->orderRepo = $orderRepo;
        $this->orderService = $orderService;
    }

    public function orders(): JsonResponse
    {
        $orders = $this->managerRepo->getOrders(Auth::id());

        return response()->json(['success' => true, 'orders' => OrderManagersResource::collection($orders)->resource]);
    }

    public function getOrder(int $orderId): JsonResponse
    {
        $order = $this->orderRepo->getOrder($orderId);
        $history = $this->orderService->history($orderId);

        return response()->json(
            [
                'success' => true,
                'order' => OrderManagersResource::make($order),
                'history' => OrderHistoryResource::collection($history)
            ]
        );
    }
}
