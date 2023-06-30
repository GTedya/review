<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

class ManagerRepo
{
    public function getOrders($userId): LengthAwarePaginator
    {
        return Order::manager($userId)->with(['geo', 'user'])->paginate();
    }

    public function getById(int $userId, int $orderId): ?Order
    {
        return Order::manager($userId)->where('id', $orderId)->with(['geo', 'user'])->first();
    }
}
