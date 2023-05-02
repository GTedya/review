<?php

namespace App\Repositories;

use App\Models\Geo;
use Illuminate\Pagination\LengthAwarePaginator;

class GeoRepo
{
    public function pagination(): LengthAwarePaginator
    {
        return Geo::query()->paginate();
    }
}
