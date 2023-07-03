<?php

namespace App\Services;

use App\Models\Rent;
use App\Models\User;
use App\Repositories\GeoRepo;
use App\Repositories\RentRepo;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RentService
{
    public function __construct(public GeoRepo $geoRepo, public RentRepo $rentRepo)
    {
    }

    /**
     * @throws ValidationException
     */
    public function create(User $user, array $data): Rent
    {
        $geo_id = $data['geo_id'];

        if ($this->geoRepo->hasChildren($geo_id)) {
            throw ValidationException::withMessages(
                ['geo_id' => 'Некорректные данные области']
            );
        };

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

    public function getRent(string $slug): Rent
    {
        /** @var Rent $rent */
        $rent = $this->rentRepo->getRentBySlug($slug);
        if ($rent == null) {
            abort(404);
        }
        return $rent;
    }
}
