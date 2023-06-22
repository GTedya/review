<?php

namespace App\Repositories;

use App\Models\OrderHistory;
use Illuminate\Support\Collection;

class OrderHistoryRepo
{
    public function getHistory(int $orderId): Collection
    {
        return OrderHistory::query()->where('order_id', $orderId)->get();
    }
}
