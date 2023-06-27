<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ManagerRepo
{
    public function getOrders(int $userId): LengthAwarePaginator
    {
        return Order::query()->whereDoesntHave('banUsers', function (Builder $query) use ($userId) {
            $query->where('manager_order_bans.user_id', $userId);
        })->with(['geo', 'user'])->paginate();
    }

    public function takeOrder(int $userId, int $orderId): ?Order
    {
        return Order::query()->whereDoesntHave('banUsers', function (Builder $query) use ($userId) {
            $query->where('manager_order_bans.user_id', $userId);
        })->where('id', $orderId)->with(['geo', 'user'])->first();
    }
}
