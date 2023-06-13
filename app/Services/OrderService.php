<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderLeasingVehicle;
use App\Models\Page\RepeatVar;
use App\Models\User;
use App\Repositories\OrderRepo;
use Illuminate\Support\Collection;
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

    /**
     * @throws ValidationException
     */
    public function editOrder(User $user, int $orderId, array $data): Order
    {
        if (!$this->orderRepo->usersOrderExists($orderId, $user->id)) {
            throw ValidationException::withMessages(['order' => 'Некорректные данные заказа']);
        }

        $order = $this->orderRepo->usersOrder($orderId, $user->id);
        /** @var Collection<RepeatVar> $oldRepeatVars */
        $oldRepeatVars = $order->leasingVehicles()->get();
        $oldItems = $this->mapVars($oldRepeatVars);
        foreach ($data['leasing']['vehicles'] as  $vehicle){
            $newItems[] = $vehicle['type_id'];
        }
        if (filled($data['leasing'] ?? null)) {
            $toDelete = (array_diff_assoc($oldItems, $newItems));

            $toCreate = array_diff_key($newItems, $oldItems);
            $toUpdate = array_diff_key($newItems, $toCreate, $toDelete);
            dd($toDelete,$toCreate, $toUpdate);


            if (filled($toDelete)) {
                $order->leasingVehicles()->delete(83);
            }
            $order->leasingVehicles()->createMany($toCreate);
        }

        if (filled($data['dealer'] ?? null)) {
            $order->dealerVehicles()->delete();
            $dataDealer = $data['dealer'];
            $order->dealerVehicles()->createMany($dataDealer['vehicles']);
        }
        $order->update($data);

        return $order;
    }
    private function deleteRepeatVars(Collection $repeatVars, array $toDelete)
    {
        $repeatVars->whereIn('id', $toDelete)
            ->each(fn (OrderLeasingVehicle $var) => $var->delete());
    }

    private function mapVars(Collection $repeatVars): array
    {
        $repeatVars = $repeatVars->mapWithKeys(function (OrderLeasingVehicle $item){
            return [$item->id => $item->type_id];
        });
        return $repeatVars->toArray();
    }
}
