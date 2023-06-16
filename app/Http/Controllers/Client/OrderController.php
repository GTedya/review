<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function create(OrderRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $order = $this->orderService->createOrder($user, $request->validated());
        $order = $order->fresh('leasing', 'dealerVehicles', 'leasingVehicles');

        return response()->json(['success' => true, 'order' => OrderResource::make($order)]);
    }

    /**
     * @throws ValidationException
     */
    public function edit(int $orderId, OrderRequest $request): JsonResponse
    {
        $userId = Auth::id();

        $order = $this->orderService->editOrder($userId, $orderId, $request->validated());

        return response()->json(['success' => true, 'order' => OrderResource::make($order)]);
    }
}
