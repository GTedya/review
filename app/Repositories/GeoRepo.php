<?php

namespace App\Repositories;

use App\Models\Geo;
use Illuminate\Support\Collection;

class GeoRepo
{
    public function list(): Collection
    {
        return Geo::query()->where('parent_id', null)->with('childrenDeep')->get();
    }

    public function hasChildren($id): bool
    {
        return Geo::query()->where('id', $id)->has('children')->exists();
    }
}
