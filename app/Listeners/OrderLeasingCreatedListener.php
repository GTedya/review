<?php

namespace App\Listeners;

use App\Events\OrderLeasingCreated;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class OrderLeasingCreatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct(private readonly Messaging $messaging)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderLeasingCreated $event): void
    {
        $order = $event->leasing->order;
        $tokens = User::query()
            ->whereHas('roles', fn(Builder $query) => $query->where('name', 'leasing_manager'))
            ->whereDoesntHave('banOrders', function (Builder $query) use ($order) {
                $query->where('order_id', $order->id);
            })
            ->whereNotNull('device_key')
            ->pluck('device_key');

        $message = CloudMessage::new()
            ->withNotification(
                Notification::create(
                    'В списке заказов новый заказ'
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
