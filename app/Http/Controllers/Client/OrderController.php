<?php

namespace App\Http\Controllers\Client;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OrderController extends OrderService
{
    public function create(OrderRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $order = $this->createOrder($user, $request->validated());
        $order = $order->fresh('leasing', 'dealer');

        return response()->json(['success' => true, 'order' => OrderResource::make($order)]);
    }
}
