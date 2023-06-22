<?php

namespace App\Observers;

use App\Models\OrderLeasing;
use Illuminate\Support\Facades\View;

class OrderLeasingObserver
{
    public bool $afterCommit = true;

    /**
     * Handle the OrderLeasing "created" event.
     */
    public function created(OrderLeasing $orderLeasing): void
    {
        View::share('order_leasing_observer', ['leasing']);
    }

    /**
     * Handle the OrderLeasing "updated" event.
     */
    public function updated(OrderLeasing $orderLeasing): void
    {
        $changes = array_keys($orderLeasing->getChanges());

        View::share('order_leasing_observer', $changes);
    }

    /**
     * Handle the OrderLeasing "deleted" event.
     */
    public function deleted(OrderLeasing $orderLeasing): void
    {
        View::share('order_leasing_observer', ['leasing', 'leasing_vehicles']);
    }
}
