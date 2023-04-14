<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ManagerRepo
{
    public function getOrders($userId): Collection
    {
        return Order::query()->whereDoesntHave('banUsers', function (Builder $query) use ($userId) {
            $query->where('manager_order_bans.user_id', $userId);
        })->get();
    }
}
