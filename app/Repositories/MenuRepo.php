<?php

namespace App\Repositories;

use App\Models\Geo;
use App\Models\MenuGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class MenuRepo
{
    public function list(): Collection
    {
        return MenuGroup::query()->orderByDesc('sort_index')->with('items', function (HasMany $query) {
            $query->orderByDesc('sort_index');
        })->get();
    }
}
