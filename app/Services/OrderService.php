<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Repositories\OrderRepo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        $order = $this->orderRepo->usersOrder($orderId, $userId) ?? throw ValidationException::withMessages(
            ['order' => 'Некорректные данные заказа']
        );
        if (filled($data['leasing'] ?? null)) {
            $oldItems = $order->leasingVehicles()->get();

            $newItems = collect($data['leasing']['vehicles'])->map(function ($item) {
                return $item['type_id'];
            });
            $toUpdate = $oldItems->whereIn('type_id', $newItems)->toArray();
            $toDelete = $oldItems->whereNotIn('type_id', $newItems)->toArray();
            $toCreate = $newItems->diffKeys($oldItems)->toArray();

            $vehiclesConnection = $order->leasingVehicles();

            if (filled($toDelete)) {
                $this->deleteVehicles($vehiclesConnection, ...$toDelete);
            }
            if (filled($toCreate)) {
                $this->createVehicles($vehiclesConnection, $data['leasing']['vehicles'], $toCreate);
            }
            if (filled($toUpdate)) {
                $this->updateVehicles($vehiclesConnection, $data['leasing']['vehicles'], ...$toUpdate);
            }
        }

        if (filled($data['dealer'] ?? null)) {
            $oldItems = $order->dealerVehicles()->get();

            $newItems = collect($data['dealer']['vehicles'])->map(function ($item) {
                return $item['type_id'];
            });
            $toUpdate = $oldItems->whereIn('type_id', $newItems)->toArray();
            $toDelete = $oldItems->whereNotIn('type_id', $newItems)->toArray();
            $toCreate = $newItems->diffKeys($oldItems)->toArray();

            $vehiclesConnection = $order->dealerVehicles();

            if (filled($toDelete)) {
                $this->deleteVehicles($vehiclesConnection, ...$toDelete);
            }
            if (filled($toCreate)) {
                $this->createVehicles($vehiclesConnection, $data['dealer']['vehicles'], $toCreate);
            }
            if (filled($toUpdate)) {
                $this->updateVehicles($vehiclesConnection, $data['dealer']['vehicles'], ...$toUpdate);
            }
        }

        $order->update($data);

        return $order;
    }

    private function deleteVehicles(HasMany $connection, ?array $toDelete): void
    {
        $connection->whereIn('id', $toDelete)->each(function ($query) {
            $query->delete();
        });
    }

    private function createVehicles(HasMany $connection, array $data, array $toCreate): void
    {
        $connection->createMany(array_intersect_key($data, $toCreate));
    }

    private function updateVehicles(HasMany $connection, array $data, array $toUpdate): void
    {
        $unity = array_intersect_key($data, $toUpdate);
        $connection->whereIn('id', $toUpdate)->each(function ($query) use ($unity) {
            $query->update($unity);
        });;
    }
}
