<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepo
{
    public function usersOrder(int $orderId, int $userId): ?Order
    {
        /** @var ?Order $order */
        $order = Order::query()->where('id', $orderId)->where('user_id', $userId)->with(
            ['leasing', 'dealerVehicles', 'leasingVehicles', 'orderHistory', 'user.files']
        )->first();

        return $order;
    }
}
