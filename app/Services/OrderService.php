<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Repositories\OrderRepo;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(public OrderRepo $orderRepo)
    {
    }

    public function createOrder(User $user, array $data): Order
    {
        DB::beginTransaction();
        /** @var Order $order */
        $order = $user->orders()->create($data);

        if (filled($data['leasing'] ?? null)) {
            $dataLeasing = $data['leasing'];
            $order->leasing()->create($dataLeasing);
            $order->leasingVehicles()->createMany($dataLeasing['vehicles']);
        }
        if (filled($data['dealer'] ?? null)) {
            $dataDealer = $data['dealer'];
            $order->dealerVehicles()->createMany($dataDealer['vehicles']);
        }
        DB::commit();
        return $order;
    }

    public function editOrder(User $user, int $orderId, array $data): Order
    {
        if (!$this->orderRepo->usersOrderExists($orderId, $user->id)) {
            throw ValidationException::withMessages(['order' => 'Некорректные данные заказа']);
        }

        $order = $this->orderRepo->usersOrder($orderId, $user->id);

        DB::beginTransaction();
        if (filled($data['leasing'] ?? null)) {
            $dataLeasing = $data['leasing'];
            $order->leasingVehicles()->delete();
            $order->leasingVehicles()->createMany($dataLeasing['vehicles']);

            unset($dataLeasing['vehicles']);
            $order->leasing()->updateOrCreate(array_diff_key($dataLeasing));
        }

        if (filled($data['dealer'] ?? null)) {
            $order->dealerVehicles()->delete();
            $dataDealer = $data['dealer'];
            $order->dealerVehicles()->createMany($dataDealer['vehicles']);
        }
        $order->update($data);

        DB::commit();

        return $order;
    }
}
