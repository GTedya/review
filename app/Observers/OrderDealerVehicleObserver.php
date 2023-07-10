<?php

namespace App\Observers;

use App\Models\OrderDealerVehicle;
use Illuminate\Support\Facades\View;

class OrderDealerVehicleObserver
{
    public bool $afterCommit = true;
    /**
     * Handle the OrderDealerVehicle "created" event.
     */
    public function created(OrderDealerVehicle $orderDealerVehicle): void
    {
        View::share('order_dealer_vehicle_observer', 'order_leasing_vehicles');
    }

    /**
     * Handle the OrderDealerVehicle "updated" event.
     */
    public function updated(OrderDealerVehicle $orderDealerVehicle): void
    {
        View::share('order_dealer_vehicle_observer', 'order_leasing_vehicles');
    }

    /**
     * Handle the OrderDealerVehicle "deleted" event.
     */
    public function deleted(OrderDealerVehicle $orderDealerVehicle): void
    {
        View::share('order_dealer_vehicle_observer', 'order_leasing_vehicles');
    }
}
