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

    public function pagination(
        ?int $perPage,
        ?array $geos,
        ?bool $with_nds,
        ?array $types,
        ?array $vehTypes
    ): LengthAwarePaginator {
        $query = Rent::query();
        if (filled($geos)) {
            $query->whereIn('geo_id', $geos);
        }
        if (filled($with_nds)) {
            $query->where('with_nds', $with_nds);
        }
        if (filled($vehTypes)) {
            $query->whereHas('rentVehicles', function ($query) use ($vehTypes) {
                $query->whereIn('type_id', $vehTypes);
            });
        }
        if (filled($types)) {
            $query->whereIn('type', $types);
        }
        return $query->whereDate('active_until', '>=', now())->where('is_published', true)->orderBy(
            'created_at',
            'desc'
        )->with(
            ['rentVehicles.type', 'geo', 'user']
        )->paginate($perPage);
    }

    public function getRentBySlug(string $slug): Rent|null
    {
        return Rent::query()->where('slug', $slug)->where('is_published', true)->with(
            ['rentVehicles.type', 'geo', 'user']
        )->first();
    }
}
