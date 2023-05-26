<?php

namespace App\Repositories;

use App\Models\Leasing;
use Illuminate\Support\Collection;

class LeasingRepo
{
    public function list(): Collection
    {
        return Leasing::orderByDesc('sort_index')->get();
    }
}
