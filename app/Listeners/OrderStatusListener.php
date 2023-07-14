<?php

namespace App\Listeners;

use App\Events\OrderUpdated;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class OrderStatusListener
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
    public function handle(OrderUpdated $event): void
    {
        $order = $event->order;
        if (!$order->wasChanged('status_id')) {
            return;
        }
        $deviceKey = $order->user->device_key;
        if (blank($deviceKey)) {
            return;
        }
        $message = CloudMessage::withTarget('token', $deviceKey)
            ->withNotification(
                Notification::create('Статус заказа изменен', "Заказ №{$order->id} {$order->status->name}")
            )
            ->withData(['screen' => 'order', 'id' => $order->id])
            ->withDefaultSounds();
        $this->messaging->send($message);
    }
}
