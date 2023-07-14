<?php

namespace App\Listeners;

use App\Events\OrderOfferCreated;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class OrderOfferListener
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
    public function handle(OrderOfferCreated $event): void
    {
        $order = $event->offer->order;
        $deviceKey = $order->user->device_key;
        if (blank($deviceKey)) {
            return;
        }
        $message = CloudMessage::withTarget('token', $deviceKey)
            ->withNotification(
                Notification::create(
                    'Поступило новое коммерческое предложение',
                    "Новое коммерческое предложение от {$event->offer->manager->name} в заказе №{$order->id}"
                )
            )
            ->withData(['screen' => 'order', 'id' => $order->id])
            ->withDefaultSounds();
        $this->messaging->send($message);
    }
}
