<?php

namespace App\Repositories;

use App\Models\Rent;
use Illuminate\Pagination\LengthAwarePaginator;

class RentRepo
{
    public function history(int $id, ?int $perPage): LengthAwarePaginator
    {
        return Rent::query()->where('user_id', $id)->orderBy('created_at', 'desc')->with(['geo', 'user'])->paginate(
            $perPage
        );
    }

    public function pagination(?int $perPage, ?array $geos, ?bool $with_nds, ?array $types): LengthAwarePaginator
    {
        $query = Rent::query();
        if (filled($geos)) {
            $query->whereIn('geo_id', $geos);
        }
        if (filled($with_nds)) {
            $query->where('with_nds', $with_nds);
        }
        if (filled($types)) {
            $query->whereHas('rentVehicles', function ($query) use ($types) {
                $query->whereIn('type_id', $types);
            });
        }
        return $query->whereDate('active_until', '>=', now())->orderBy('created_at', 'desc')->with(
            ['rentVehicles.type', 'geo', 'user']
        )->paginate($perPage);
    }
}
