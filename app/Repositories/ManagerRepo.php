<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ManagerRepo
{
    public function getOrders($userId): LengthAwarePaginator
    {
        return Order::query()->whereDoesntHave('banUsers', function (Builder $query) use ($userId) {
            $query->where('manager_order_bans.user_id', $userId);
        })->paginate();
    }
}
