<?php

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Facades\View;

class OrderObserver
{
    public bool $afterCommit = true;

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        $changes = array_flip(array_keys($order->getChanges()));
        unset($changes['updated_at']);
        View::share('order_observer_update', array_flip($changes));
    }
}
