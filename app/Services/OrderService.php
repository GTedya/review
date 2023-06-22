<?php

namespace App\Services;

use App\Events\OrderUpdate;
use App\Models\Order;
use App\Models\OrderDealerVehicle;
use App\Models\OrderLeasingVehicle;
use App\Models\User;
use App\Repositories\OrderHistoryRepo;
use App\Repositories\OrderRepo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(public OrderRepo $orderRepo, public OrderHistoryRepo $orderHistoryRepo)
    {
    }

    public function history(int $orderId): Collection
    {
        return $this->orderHistoryRepo->getHistory($orderId);
    }

    public function createOrder(User $user, array $data): Order
    {
        // TODO: добавить поле инн
        $data['inn'] = $user->inn;
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
            $oldIds = $oldItems->map(fn(OrderLeasingVehicle $item) => $item->id);

            $newItems = collect($data['leasing']['vehicles'])->mapWithKeys(function ($item) {
                return [$item['id'] ?? Str::random() => $item];
            });

            $toDelete = $oldItems->whereNotIn('id', $newItems->keys());
            $toCreate = $newItems->whereNotIn('id', $oldIds);
            $toUpdate = $oldItems->whereIn('id', $newItems->keys());

            DB::beginTransaction();

            if (filled($toDelete)) {
                $this->deleteVehicles($toDelete);
            }

            if (filled($toCreate)) {
                $vehiclesConnection = $order->leasingVehicles();
                $this->createVehicles($vehiclesConnection, $toCreate);
            }

            if (filled($toUpdate)) {
                $this->updateVehicles($toUpdate, $newItems);
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

            $oldIds = $oldItems->map(fn(OrderDealerVehicle $item) => $item->id);

            $newItems = collect($data['dealer']['vehicles'])->mapWithKeys(function ($item) {
                return [$item['id'] ?? Str::random() => $item];
            });

            $toDelete = $oldItems->whereNotIn('id', $newItems->keys());
            $toCreate = $newItems->whereNotIn('id', $oldIds);
            $toUpdate = $oldItems->whereIn('id', $newItems->keys());

            DB::beginTransaction();

            if (filled($toDelete)) {
                $this->deleteVehicles($toDelete);
            }

            if (filled($toCreate)) {
                $vehiclesConnection = $order->dealerVehicles();
                $this->createVehicles($vehiclesConnection, $toCreate);
            }

            if (filled($toUpdate)) {
                $this->updateVehicles($toUpdate, $newItems);
            }
            DB::commit();
        } else {
            $order->dealerVehicles()->delete();
        }

        $order->update($data);

        event(new OrderUpdate($order));

        return $order;
    }

    private function deleteVehicles(Collection $toDelete): void
    {
        $toDelete->each(fn($vehicle) => $vehicle->delete());
    }

    private function createVehicles(HasMany $connection, Collection $data): void
    {
        $connection->createMany($data);
    }

    private function updateVehicles(Collection $toUpdate, Collection $newItems): void
    {
        foreach ($toUpdate as $vehicle) {
            $vehicle->update($newItems[$vehicle->id] ?? []);
        }
    }
}
