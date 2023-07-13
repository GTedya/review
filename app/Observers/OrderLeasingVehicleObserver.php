<?php

namespace App\Observers;

use App\Models\OrderLeasingVehicle;
use Illuminate\Support\Facades\View;

class OrderLeasingVehicleObserver
{
    public bool $afterCommit = true;

    /**
     * Handle the OrderLeasingVehicle "created" event.
     */
    public function created(OrderLeasingVehicle $orderLeasingVehicle): void
    {
        View::share('order_leasing_vehicle_observer', 'order_leasing_vehicles');
    }

    /**
     * Handle the OrderLeasingVehicle "updated" event.
     */
    public function updated(OrderLeasingVehicle $orderLeasingVehicle): void
    {
        View::share('order_leasing_vehicle_observer', 'order_leasing_vehicles');
    }

    /**
     * Handle the OrderLeasingVehicle "deleted" event.
     */
    public function deleted(OrderLeasingVehicle $orderLeasingVehicle): void
    {
        View::share('order_leasing_vehicle_observer', 'order_leasing_vehicles');
    }

    public function forceDeleted(OrderLeasingVehicle $orderLeasingVehicle): void
    {
        View::share('order_leasing_vehicle_observer', 'order_leasing_vehicles');
    }
}
