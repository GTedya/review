<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepo
{
    public function usersOrder(int $orderId, int $userId): ?Order
    {
        return Order::query()->where('id', $orderId)->where('user_id', $userId)->first();
    }

    public function usersOrderExists(int $orderId, int $userId): bool
    {
        return Order::query()->where('id', $orderId)->where('user_id', $userId)->exists();
    }
}
