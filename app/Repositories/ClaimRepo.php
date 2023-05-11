<?php

namespace App\Repositories;

use App\Models\Claim;
use App\Models\Geo;
use Illuminate\Support\Collection;

class ClaimRepo
{
    public function list(): Collection
    {
        return Geo::all();
    }

    public function create($data): ?Claim
    {
        return Claim::create($data);
    }
}
