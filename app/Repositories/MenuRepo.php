<?php

namespace App\Repositories;

use App\Models\Geo;
use App\Models\MenuGroup;
use Illuminate\Support\Collection;

class MenuRepo
{
    public function list(): Collection
    {
        return MenuGroup::with('items')->get();
    }
}
