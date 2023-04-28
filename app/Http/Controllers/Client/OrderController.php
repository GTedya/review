<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function create(OrderRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $order = $user->orders()->create($request->validated());
        if ($request->input('leasing')) {
            $leasing = $order->leasing()->create($request->input('leasing'));
            $leasingVehicles = $leasing->vehicles()->create($request->input('leasing')['vehicles']);
        };
        return response()->json(['success' => true, 'req' => new OrderResource($order)]);
    }
}
