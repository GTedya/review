<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderDealer;
use App\Models\OrderLeasing;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(User $user, array $data): Order
    {
        DB::beginTransaction();
        /** @var Order $order */
        $order = $user->orders()->create($data);

        if (filled($data['leasing'] ?? null)) {
            $dataLeasing = $data['leasing'];
            /** @var OrderLeasing $leasing */
            $leasing = $order->leasing()->create($dataLeasing);
            foreach ($dataLeasing['vehicles'] as $dataVehicle) {
                $leasing->vehicles()->create($dataVehicle);
            }
        }
        if (filled($data['dealer'] ?? null)) {
            $dataDealer = $data['dealer'];
            /** @var OrderDealer $dealer */
            $dealer = $order->dealer()->create($dataDealer);
            foreach ($dataDealer['vehicles'] as $dataVehicle) {
                $dealer->vehicles()->create($dataVehicle);
            }
        }
        DB::commit();
        return $order;
    }
}
