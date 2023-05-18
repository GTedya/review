<?php

namespace App\Services;

use App\Models\Rent;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
}
