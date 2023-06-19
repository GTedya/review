<?php

namespace App\Observers;

use App\Models\OrderLeasing;

class OrderLeasingObserver
{
    /**
     * Handle the OrderLeasing "created" event.
     */
    public function created(OrderLeasing $orderLeasing): void
    {
        //
    }

    /**
     * Handle the OrderLeasing "updated" event.
     */
    public function updated(OrderLeasing $orderLeasing): void
    {
        dd($orderLeasing);
    }

    /**
     * Handle the OrderLeasing "deleted" event.
     */
    public function deleted(OrderLeasing $orderLeasing): void
    {
        //
    }

    /**
     * Handle the OrderLeasing "restored" event.
     */
    public function restored(OrderLeasing $orderLeasing): void
    {
        //
    }

    /**
     * Handle the OrderLeasing "force deleted" event.
     */
    public function forceDeleted(OrderLeasing $orderLeasing): void
    {
        //
    }
}
