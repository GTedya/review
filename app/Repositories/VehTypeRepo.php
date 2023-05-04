<?php

namespace App\Repositories;

use App\Models\VehicleType;
use Illuminate\Support\Collection;

class VehTypeRepo
{
    public function list(): Collection
    {
        return VehicleType::query()
            ->where('parent_id', null)
            ->with('childrenDeep')
            ->get();
    }
}
