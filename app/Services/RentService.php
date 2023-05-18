<?php

namespace App\Services;

use App\Models\Rent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RentService
{
    public function create(User $user, array $data): Rent
    {
        DB::beginTransaction();
        $data['active_until'] = now()->addMonth();

        /** @var Rent $rent */
        $rent = $user->rents()->create($data);
        $rent->rentVehicles()->createMany($data['rent_vehicles']);

        DB::commit();
        return $rent;
    }

    /**
     * @throws ValidationException
     */
    public function extend(int $userId, int $rentId): void
    {
        $result = Rent::query()
            ->where('id', $rentId)
            ->where('user_id', $userId)
            ->update(['active_until' => now()->addMonth()]);
        if (!$result) {
            throw ValidationException::withMessages(['message' => 'Не удалось продлить объявление']);
        }
    }
}
