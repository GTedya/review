<?php

namespace App\Repositories;

use App\Models\Geo;
use Illuminate\Support\Collection;

class GeoRepo
{
    public function list(): Collection
    {
        return Geo::all();
    }
}
