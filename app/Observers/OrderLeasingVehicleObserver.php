<?php

namespace App\Observers;

use App\Models\OrderLeasingVehicle;

class OrderLeasingVehicleObserver
{
    /**
     * Handle the OrderLeasingVehicle "created" event.
     */
    public function created(OrderLeasingVehicle $orderLeasingVehicle): void
    {
        //
    }

    /**
     * Handle the OrderLeasingVehicle "updated" event.
     */
    public function updated(OrderLeasingVehicle $orderLeasingVehicle): void
    {
        $orderLeasingVehicle->order->orderHistory()->create(['edited' => 'orderLeasing']);
    }

    /**
     * Handle the OrderLeasingVehicle "deleted" event.
     */
    public function deleted(OrderLeasingVehicle $orderLeasingVehicle): void
    {
        //
    }

    /**
     * Handle the OrderLeasingVehicle "restored" event.
     */
    public function restored(OrderLeasingVehicle $orderLeasingVehicle): void
    {
        //
    }

    /**
     * Handle the OrderLeasingVehicle "force deleted" event.
     */
    public function forceDeleted(OrderLeasingVehicle $orderLeasingVehicle): void
    {
        //
    }
}
