<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class ManagerRepo
{
    public function getOrders($userId): LengthAwarePaginator
    {
        return Order::manager($userId)->with(['geo', 'user', 'leasing'])->paginate();
    }

    public function getById(int $userId, int $orderId): ?Order
    {
        /** @var ?Order $order */
        $order = Order::manager($userId)->where('id', $orderId)->with(
            ['geo', 'user', 'leasing', 'leasingVehicles', 'dealerVehicles', 'orderHistory', 'managers']
        )->first();
        return $order;
    }

    public function takeOrder(User $user, int $orderId): array
    {
        return $user->takenOrders()->syncWithoutDetaching(['order_id' => $orderId]);
    }
}
