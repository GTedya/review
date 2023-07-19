<?php

namespace App\Services;

use App\Events\OrderDealerCreated;
use App\Events\OrderManualUpdated;
use App\Models\Order;
use App\Models\OrderDealerVehicle;
use App\Models\OrderLeasingVehicle;
use App\Models\User;
use App\Models\UserFile;
use App\Repositories\GeoRepo;
use App\Repositories\ManagerRepo;
use App\Repositories\OrderRepo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(public OrderRepo $orderRepo, public GeoRepo $geoRepo, public ManagerRepo $managerRepo)
    {
    }

    /**
     * @throws ValidationException
     */
    public function createOrder(User $user, array $data): Order
    {
        $data['inn'] = $user->company?->inn;
        if (blank($data['inn'])) {
            throw ValidationException::withMessages(
                ['inn' => 'Вы не заполнили поле ИНН в личном кабинете']
            );
        };

        $geo_id = $data['geo_id'] ?? null;

        if ($this->geoRepo->hasChildren($geo_id)) {
            throw ValidationException::withMessages(
                ['geo_id' => 'Некорректные данные области']
            );
        };
        DB::beginTransaction();
        /** @var Order $order */
        $order = $user->orders()->create($data);

        if (filled($data['files'] ?? null)) {
            $repeats = $user->files->pluck('type_id')->intersect(array_keys($data['files']));
            if (filled($repeats)) {
                $messages = $repeats->mapWithKeys(function ($id) {
                    return ["files.$id" => 'Файл этого типа уже загружен'];
                })->toArray();
                throw ValidationException::withMessages($messages);
            }

            foreach ($data['files'] as $id => $files) {
                /** @var UserFile $userFile */
                $userFile = $user->files()->create(['type_id' => $id]);
                foreach ($files as $file) {
                    $userFile->addMedia($file)->toMediaCollection();
                }
            }
        };

        if (filled($data['leasing'] ?? null)) {
            $dataLeasing = $data['leasing'];
            $order->leasing()->create($dataLeasing);
            $order->leasingVehicles()->createMany($dataLeasing['vehicles']);
        }

        if (filled($data['dealer'] ?? null)) {
            $dataDealer = $data['dealer'];
            $order->dealerVehicles()->createMany($dataDealer['vehicles']);
            $dispatchEvent = true;
        }

        DB::commit();

        if ($dispatchEvent ?? false) {
            OrderDealerCreated::dispatch($order);
        }

        return $order;
    }

    /**
     * @throws ValidationException
     */
    public function getClientOrder($id): Order
    {
        $usersOrder = $this->orderRepo->usersOrder($id, Auth::id());
        if ($usersOrder == null) {
            abort(403);
        }
        return $usersOrder;
    }

    /**
     * @throws ValidationException
     */
    public function editOrder(int $userId, int $orderId, array $data): Order
    {
        /** @var Order $order */
        $order = $this->orderRepo->usersOrder($orderId, $userId);

        if ($order == null) {
            abort(403);
        }

        $geo_id = $data['geo_id'] ?? null;

        if ($this->geoRepo->hasChildren($geo_id)) {
            throw ValidationException::withMessages(
                ['geo_id' => 'Некорректные данные области']
            );
        };

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

        OrderManualUpdated::dispatch($order);

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

    /**
     * @throws ValidationException
     */
    public function managerTakeOrder(User $user, int $orderId): void
    {
        if (blank($this->managerRepo->getById($user->id, $orderId))) {
            abort(403);
        }
        if ($user->takenOrders->contains('id', $orderId)) {
            throw ValidationException::withMessages(['order' => 'Вы уже взяли в работу данный заказ']);
        }
        $this->managerRepo->takeOrder($user, $orderId);
    }
}
