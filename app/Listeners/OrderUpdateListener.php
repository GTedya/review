<?php

namespace App\Listeners;

use App\Events\OrderManualUpdated;
use App\Models\Order;
use Illuminate\Support\Facades\View;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class OrderUpdateListener
{

    /**
     * Create the event listener.
     */
    public function __construct(private Messaging $messaging)
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
            $this->notificationsToManagers($event->order);
        }
    }

    private function notificationsToManagers(Order $order): void
    {
        $tokens = $order->managers->whereNotNull('device_key')->pluck('device_key');
        $message = CloudMessage::new()
            ->withNotification(
                Notification::create(
                    'Один из взятых в работу заказов был изменен',
                    "В заказе №{$order->id} произошли изменения"
                )
            )
            ->withData(['screen' => 'order', 'id' => $order->id])
            ->withDefaultSounds();

        $chunks = $tokens->unique()->chunk(500);

        foreach ($chunks as $tokens) {
            $this->messaging->sendMulticast($message, $tokens->toArray());
        }
    }
}
