<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\User;
use App\Repositories\ManagerRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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

    /**
     * @throws ValidationException
     */
    public function takeOrder(int $orderId): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if (blank($this->managerRepo->takeOrder($user->id, $orderId))) {
            throw ValidationException::withMessages(
                ['order' => 'Некорректные данные заказа']
            );
        }

        $user->takenOrders()->syncWithoutDetaching(['order_id' => $orderId]);
        return response()->json(['success' => true]);
    }
}
