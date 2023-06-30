<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\User;
use App\Repositories\ManagerRepo;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public ManagerRepo $managerRepo;

    public function __construct(ManagerRepo $managerRepo, OrderService $orderService)
    {
        $this->managerRepo = $managerRepo;
        $this->orderService = $orderService;
    }

    public function orders(): JsonResponse
    {
        $orders = $this->managerRepo->getOrders(Auth::id());

        return response()->json(['success' => true, 'orders' => OrderResource::collection($orders)->resource]);
    }

    /**
     * @throws ValidationException
     */
    public function takeOrder(int $orderId): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $this->orderService->managerTakeOrder($user, $orderId);

        return response()->json(['success' => true]);
    }
}
