<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Repositories\OrderRepo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    public function editOrder(int $userId, int $orderId, array $data): Order
    {
        /** @var Order $order */
        $order = $this->orderRepo->usersOrder($orderId, $userId);

        if ($order == null) {
            throw ValidationException::withMessages(
                ['order' => 'Некорректные данные заказа']
            );
        }

        if (filled($data['leasing'] ?? null)) {
            $oldItems = $order->leasingVehicles()->get();

            $newItems = collect($data['leasing']['vehicles'])->map(function ($item) {
                return $item['id'] ?? null;
            });

            $toUpdate = $oldItems->whereIn('id', $newItems);
            $toDelete = $oldItems->whereNotIn('id', $newItems);
            $toCreate = $newItems->whereNull();

            $vehiclesConnection = $order->leasingVehicles();
            DB::beginTransaction();
            if (filled($toDelete)) {
                $this->deleteVehicles($oldItems, $toDelete);
            }

            if (filled($toUpdate)) {
                $this->updateVehicles($data['leasing']['vehicles'], $toUpdate);
            }

            if (filled($toCreate)) {
                $this->createVehicles($vehiclesConnection, $data['leasing']['vehicles']);
            }

            unset($data['leasing']['vehicles']);
            $order->leasing()->updateOrCreate(['order_id' => $order->id], $data['leasing']);

            DB::commit();
        } else {
            $order->leasing()->delete();
            $order->leasingVehicles()->delete();
        }

        if (filled($data['dealer'] ?? null)) {
            $oldItems = $order->dealerVehicles()->get();

            $newItems = collect($data['dealer']['vehicles'])->map(function ($item) {
                return $item['id'] ?? null;
            });

            $toUpdate = $oldItems->whereIn('id', $newItems);
            $toDelete = $oldItems->whereNotIn('id', $newItems);
            $toCreate = $newItems->whereNull();

            $vehiclesConnection = $order->dealerVehicles();
            DB::beginTransaction();
            if (filled($toDelete)) {
                $this->deleteVehicles($oldItems, $toDelete);
            }

            if (filled($toUpdate)) {
                $this->updateVehicles($data['dealer']['vehicles'], $toUpdate);
            }

            if (filled($toCreate)) {
                $this->createVehicles($vehiclesConnection, $data['dealer']['vehicles']);
            }
            DB::commit();
        } else {
            $order->dealerVehicles()->delete();
        }

        $order->update($data);

        return $order;
    }

    private function deleteVehicles(Collection $oldItems, Collection $toDelete): void
    {
        $oldItems->whereIn(
            'id',
            $toDelete->map(function ($item) {
                return $item['id'];
            })
        )->each(fn($vehicle) => $vehicle->delete());
    }

    private function createVehicles(HasMany $connection, array $data): void
    {
        foreach ($data as $newVehicle) {
            if (!array_key_exists('id', $newVehicle)) {
                $connection->create($newVehicle);
            }
        };
    }

    private function updateVehicles(array $data, Collection $toUpdate): void
    {
        foreach ($data as $updatedVehicle) {
            if (array_key_exists('id', $updatedVehicle)) {
                $toUpdate->each(function (Model $item) use ($updatedVehicle) {
                    if ($updatedVehicle['id'] == $item['id']) {
                        $item->update($updatedVehicle);
                    }
                });
            }
        }
    }
}
