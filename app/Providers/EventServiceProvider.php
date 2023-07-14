<?php

namespace App\Providers;

use App\Events\OrderManualUpdated;
use App\Events\OrderOfferCreated;
use App\Events\OrderUpdated;
use App\Listeners\OrderOfferListener;
use App\Listeners\OrderStatusListener;
use App\Listeners\OrderUpdateListener;
use App\Models\Order;
use App\Models\OrderDealerVehicle;
use App\Models\OrderLeasing;
use App\Models\OrderLeasingVehicle;
use App\Observers\OrderDealerVehicleObserver;
use App\Observers\OrderLeasingObserver;
use App\Observers\OrderLeasingVehicleObserver;
use App\Observers\OrderObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderManualUpdated::class => [
            OrderUpdateListener::class,
        ],
        OrderUpdated::class => [
            OrderStatusListener::class,
        ],
        OrderOfferCreated::class => [
            OrderOfferListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Order::observe(OrderObserver::class);
        OrderLeasing::observe(OrderLeasingObserver::class);
        OrderLeasingVehicle::observe(OrderLeasingVehicleObserver::class);
        OrderDealerVehicle::observe(OrderDealerVehicleObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
