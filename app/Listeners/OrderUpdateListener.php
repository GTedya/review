<?php

namespace App\Listeners;

use App\Events\OrderManualUpdated;
use Illuminate\Support\Facades\View;

class OrderUpdateListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderManualUpdated $event): void
    {
        $changes = [];
        $order = View::shared('order_observer_update');
        $orderLeasing = View::shared('order_leasing_observer');
        $orderLeasingVehicles = View::shared('order_leasing_vehicle_observer');
        $orderDealerVehicles = View::shared('order_dealer_vehicle_observer');

        if (filled($order)) {
            $changes = array_merge($changes, $order);
        }
        if (filled($orderLeasing)) {
            $changes = array_merge($changes, $orderLeasing);
        }
        if (filled($orderLeasingVehicles)) {
            $changes[] = $orderLeasingVehicles;
        }
        if (filled($orderDealerVehicles)) {
            $changes[] = $orderDealerVehicles;
        }

        if (filled($changes)) {
            $event->order->orderHistory()->create(['edited' => $changes]);
        }
    }
}
