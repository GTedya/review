<?php

namespace App\Repositories;

use App\Models\OrderHistory;
use Illuminate\Support\Collection;

class OrderHistoryRepo
{
    public function getHistory(int $orderId, ?int $count): Collection
    {
        $count = ($count == null) ? 10 : $count;
        return OrderHistory::query()->where('order_id', $orderId)->latest()->take($count)->get();
    }
}
